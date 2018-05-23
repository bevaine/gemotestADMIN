<?php
/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 09.03.2018
 * Time: 9:49
 * @var integer time_start
 */
const version = 1;
const path_updater = '/storage/.kodi/userdata/updater/';

/**
 *
 * selfUpdate
 *
 * Класс для обновления одного файла файлом из удаленного источника.
 * Адрес источника передается в конструктор. Скрипт загружает файл во временную папку
 *
 */
class selfUpdate
{
    const TIMEOUT_SOCKET = 15;

    /**
     *
     * @var string директория на сервере, куда загружается обновление, чтобы потом заменить работающий скрипт index.php
     */
    const PATH_UPDATE	 = 'updates/download/';
    /**
     *
     * @var string директория для хранения прошлой версии скрипта
     */
    const PATH_BACKUP	 = 'updates/backup/';

    /**
     *
     * @var string корневая директория установки
     */
    private $root_path;

    /**
     *
     * @var int размер загружаемого файла
     */
    private $content_length = 0;

    /**
     *
     * @var string хеш загружаемого файла
     */
    private $content_md5 = null;

    /**
     *
     * @var int текущий размер загруженного файла
     */
    private $current_content_length = 0;

    /**
     *
     * @var string путь до источника обновлений
     */
    private $update_uri = null;

    /**
     *
     * @var resource
     */
    private $download_stream = null;

    /**
     * selfUpdate constructor.
     * @param null $root_path
     */
    function __construct($root_path = null)
    {
        if(!isset(self::$root_path)){
            $this->root_path =  self::formatPath($root_path ? $root_path : dirname(__FILE__)).'/';
        }
    }

    /**
     * Основной метод загрузки и установки обновления
     * @param $update_uri
     * @param $target
     * @throws Exception
     */
    public function execute($update_uri,$target)
    {
        $this->update_uri = $update_uri;
        $target = self::formatPath($target).'/';
        $target = preg_replace('@(^|/)\.\./@','/',$target);
        $env = self::onBeforeUpdate();

        try {
            $this->cleanupPath(self::PATH_UPDATE);
            $download_file = $this->download();
            $this->replace($download_file,$target);
            $this->cleanupPath(self::PATH_UPDATE);

            self::onAfterUpdate($env);

        } catch(Exception $ex){
            //В случае ошибки возвращаем переменные окружения в исходное состояние
            self::onAfterUpdate($env);
            //и очищаем директорию от временных файлов
            $this->cleanupPath(self::PATH_UPDATE,true);
            throw $ex;
        }
    }

    /**
     * Подготавливаем окружение к обновлению
     * @return array()
     */
    private static function onBeforeUpdate()
    {
        $env = array();
        $env['session_id'] = session_id();
        if($env['session_id']){
            //KNOWHOW - на длительных операциях открытая сессия блокирует другие скрипты (с одинаковым идентификатором сессии),
            // вынуждая их ожидать завершения текущего процесса
            session_write_close();
        }

        //KNOWHOW не даем завершаться скрипту даже если браузер закрыл соединение
        ignore_user_abort(true);
        return $env;
    }

    /**
     * Возвращаем окружение в исходное состояние
     * @param $env array
     * @return void
     */
    private static function onAfterUpdate($env)
    {
        if($env['session_id']){
            session_start();
        }
    }

    /**
     * Загрузка файла с удаленного сервера.
     * Определяет подходящий метод загрузки в зависимости от серверного окружения.
     *
     * @throws Exception
     * @return string downloaded file_path
     */
    private function download()
    {
        $name = basename(preg_replace('/(\?.*)/','',$this->update_uri));
        $download_file = self::formatPath(self::PATH_UPDATE.'/'.$name);

        try {
            $this->download_stream=$this->fopen($download_file,'wb');
            if (!$this->download_stream){
                throw new Exception("Не могу создать временный файл {$download_file}");
            }

            if ($this->curlAvailable()){
                //при доступности cURL используем его, так как метод более гибкий
                $this->downloadCurl();
            } elseif($this->fopenAvailable()){
                //иначе, если allow_fopen_url = On, пробуем получить обновление через fopen()
                $this->downloadFopen();
            } else{
                throw new Exception('Нет подходящего транспорта для установки обновления (не поддерживаются ни cURL, ни allow_fopen_url)');
            }

            if($this->download_stream && is_resource($this->download_stream)){
                fclose($this->download_stream);
            }

            //проверяем длину ответа от сервера - она может не совпадать с заявленной
            // - в большую сторону в случае допвывода со стороны сервера ошибок и т.п.
            // - в меньшую в случае обрыва соединения
            if ($this->content_length && ($real_content_length = filesize($this->root_path.$download_file)) && ($this->content_length != $real_content_length)){
                throw new Exception(sprintf("Неверный размер файла. Ожидали %d, а получили %d байт.",$this->content_length,$real_content_length));
            }

            //проверяем md5 хеш загруженного файла
            if($this->content_md5 && ($md5 = md5_file($this->root_path.$download_file)) &&  (strcasecmp ($this->content_md5,$md5)!=0)){
                throw new Exception(sprintf("Неверная md5-хеш файла. Ожидали %s, а получили %s",$this->content_md5,$md5));
            }

            return $download_file;

        } catch(Exception $ex){

            if($this->download_stream && is_resource($this->download_stream)){
                fclose($this->download_stream);
            }
            if($download_file && file_exists($this->root_path.$download_file)){
                @unlink($this->root_path.$download_file);
            }
            throw $ex;
        }
    }


    /**
     * Загрузка файла с удаленного сервера через fopen()
     * Для работы необходимо, чтобы в настройках PHP было allow_fopen_url = On
     * @throws Exception
     */
    private function downloadFopen()
    {
        $source_stream = null;
        try {
            //по умолчанию таймаут на открытие ресурсов составляет 30 - это слишком много, чтобы узнать, что сети нет, поэтому ставим меньший таймаут
            $default_socket_timeout = ini_set('default_socket_timeout', self::TIMEOUT_SOCKET);
            $source_stream = fopen($this->update_uri, 'r');
            ini_set('default_socket_timeout', $default_socket_timeout);

            if(!$source_stream){
                throw new Exception("Ошибка подключения к источнику обновлений [{$this->update_uri}].");
            }

            $this->getStreamInfo($source_stream);

            $retry_counter = 0;
            while (
                ($delta=stream_copy_to_stream($source_stream,$this->download_stream,102400))
                //бывает, что последние байты ответа сервер отдает очень неохотно - nginx и т.п.
                ||( $this->content_length && ($this->current_content_length<$this->content_length) && (++$retry_counter<20) )
                ||( !$this->content_length && (++$retry_counter<3) )
            ){
                if($delta){
                    $this->current_content_length += $delta;
                    $retry_counter = 0;
                }else{
                    sleep(3);
                }
            }
            fclose($source_stream);
        } catch(Exception $ex){
            if($source_stream && is_resource($source_stream)){
                fclose($source_stream);
            }
            throw $ex;
        }
    }

    /**
     * Загрузка файла с удаленного сервера с помощью cURL
     *
     * @throws Exception
     * @return void
     */
    private function downloadCurl()
    {
        try{
            $ch = null;
            if (!($ch = curl_init()) ){
                throw new Exception('err_curlinit');
            }

            if ( curl_errno($ch) != 0 ){
                throw new Exception('err_curlinit'.curl_errno($ch).' '.curl_error($ch));
            }
            $curl_options = array(
                CURLOPT_HEADER				=> 0,
                CURLOPT_RETURNTRANSFER		=> 1,
                CURLOPT_TIMEOUT				=> self::TIMEOUT_SOCKET*60,
                CURLOPT_CONNECTTIMEOUT		=> self::TIMEOUT_SOCKET,
                CURLE_OPERATION_TIMEOUTED	=> self::TIMEOUT_SOCKET*60,
                CURLOPT_BINARYTRANSFER		=> true,
                //KNOWHOW переопределенная функция записи позволяет дополнительно фиксировать информацию о переданном размере
                CURLOPT_WRITEFUNCTION		=> array(&$this,'curlWriteHandler'),
                //KNOWHOW добавляем хук для чтения заголовков, чтобы узнать размер передаваемого файла и его md5 хеш
                CURLOPT_HEADERFUNCTION		=> array(&$this,'curlHeaderHandler'),
                CURLOPT_URL					=> $this->update_uri,
                //TODO на ряде хостингов curl работает только через прокси, который необходимо указать в настройках
            );
            foreach($curl_options as $param=>$option){
                curl_setopt($ch, $param, $option);
            }
            $res = curl_exec($ch);
            if ($errno = curl_errno($ch)) {
                $message = "Curl error: {$errno}# ".curl_error($ch)." at [{$this->update_uri}]";
                throw new Exception($message);
            }
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($status != 200){
                throw new Exception("Неверный ответ сервера {$this->update_uri}",$status);
            }
            curl_close($ch);

        } catch(Exception $ex) {
            if ($ch){
                curl_close($ch);
            }
            throw $ex;
        }
    }

    /**
     * обработчик чтения заголовков для curl
     *
     * @param $ch
     * @param $header
     * @return int
     */
    private function curlHeaderHandler($ch,$header)
    {
        $header_matches = null;
        if(preg_match('/content-length:\s*(\d+)/i',$header,$header_matches)) {
            $this->content_length = intval($header_matches[1]);
        }elseif(preg_match('/content-md5:\s*([\da-f]{32})/i',$header,$header_matches)){
            $this->content_md5=$header_matches[1];
        }
        return strlen($header);
    }

    /**
     * обработчик записи в файл для curl
     * @param $ch
     * @param $chunk
     * @return bool|int
     * @throws Exception
     */
    private function curlWriteHandler($ch,$chunk)
    {
        $size = 0;
        if ($this->download_stream && is_resource($this->download_stream)) {
            $size = fwrite($this->download_stream,$chunk);
            $this->current_content_length += $size;
        } else {
            throw new Exception('Ошибка сохранения файла на сервере');
        }
        return $size;
    }

    /**
     * Replace current version by merged old and updated files
     * @param $source_path
     * @param $target_path
     * @return bool|string
     * @throws Exception
     */
    private function replace($source_path,$target_path)
    {
        $target_path = self::formatPath($target_path);
        $source_path = self::formatPath($source_path);
        $backup_path = false;
        if (file_exists($this->root_path.$target_path)){
            $backup_path = self::PATH_BACKUP;
            $backup_path = self::formatPath($backup_path);
            $this->cleanupPath($backup_path);
            $this->mkdir($backup_path);
            $backup_path .= '/'.basename($target_path);
        }
        if ($backup_path){
            if(!$this->rename($target_path,$backup_path)){
                throw new Exception("Ошибка создания бекапа {$target_path} в папке {$backup_path}");
            }
        }

        if(!$this->rename($source_path,$target_path)){
            //rollback rename
            if($backup_path){
                $this->rename($backup_path,$target_path);
            }
            throw new Exception("Ошибка обновления {$target_path} в {$source_path}");
        }
        return $backup_path;
    }

    /**
     * "Настойчивое" переименование
     * @param $oldname
     * @param $newname
     * @return bool
     */
    private function rename($oldname,$newname)
    {
        $result = false;
        if(@rename($this->root_path.$oldname,$this->root_path.$newname)
            ||sleep(3)
            ||@rename($this->root_path.$oldname,$this->root_path.$newname)){
            $result = true;
        }
        return $result;
    }

    /**
     * Очистка директории от файлов
     * @param $paths
     * @param bool $skip_directory
     * @throws Exception
     */
    private function cleanupPath($paths,$skip_directory = false)
    {
        foreach((array)$paths as $path){
            $dir = null;
            try{
                if(file_exists($this->root_path.$path)){
                    $dir=opendir($this->root_path.$path);
                    while (false!==($current_path=readdir($dir))){
                        if(($current_path != '.' )&&($current_path != '..')){
                            if(is_dir($this->root_path.$path.'/'.$current_path)){
                                $this->cleanupPath($path.'/'.$current_path,$skip_directory);
                            }else{
                                if(!@unlink($this->root_path.$path.'/'.$current_path)){
                                    throw new Exception("Не могу удалить файл {$path}/{$current_path}");
                                }
                            }
                        }
                    }
                    closedir($dir);
                    if(!@rmdir($this->root_path.$path)&&!$skip_directory){
                        throw new Exception("Не могу удалить директорию {$path}");
                    }
                }
            }catch(Exception $ex){
                if($dir&&is_resource($dir)){
                    closedir($dir);
                }
                throw $ex;
            }
        }
    }

    /**
     * Приводим пути к nix виду
     *
     * windows поймет и такие, а в случае использования правил постобработки с использованием регулярных выражения последние упрощаются
     * @param $path string
     * @return string
     */
    private static function formatPath($path)
    {
        $path = preg_replace('@([/\\\\]+)@','/',$path);
        return preg_replace('@/$@','',$path);
    }

    /**
     * Создание директорий с дополнительными проверками на права записи
     * @param $target_path
     * @param int $mode
     * @throws Exception
     */
    private function mkdir($target_path,$mode = 0777)
    {
        if(!file_exists($this->root_path.$target_path)){
            if(!mkdir($this->root_path.$target_path,$mode&0777,true)){
                throw new Exception("не могу создать директорию {$target_path}");
            }
        }elseif(!is_dir($this->root_path.$target_path)){
            throw new Exception("Не могу создать директорию {$target_path}, так как есть файл с таким изменем");

        }elseif(!is_writable($this->root_path.$target_path)){
            throw new Exception("{$target_path} должна быть доступна по записи. Установите необходимые права доступа.");
        }
    }

    /**
     * Проверяем возможность использовать cURL
     *
     * @return boolean
     */
    private function curlAvailable()
    {
        return extension_loaded('curl') && function_exists('curl_init') && preg_match('/https?:\/\//',$this->update_uri);
    }


    /**
     * Проверяем возможность использовать fopen
     *
     * @return boolean
     */
    private function fopenAvailable()
    {
        $result = false;
        if(stream_is_local($this->update_uri)){
            $result = true;
        }else{
            $scheme = parse_url($this->update_uri,PHP_URL_SCHEME);
            if($scheme == 'https'){
                $scheme = 'http';
            }
            $result = ini_get('allow_url_fopen') && in_array($scheme,stream_get_wrappers());
        }
        return $result;
    }

    /**
     * Читаем метаданные загружаемого файла
     *
     * @param $source_stream resource
     * @param $download_content_length int
     * @return void
     */
    private function getStreamInfo($source_stream,$download_content_length=4096)
    {
        $stream_meta_data=stream_get_meta_data($source_stream);

        //KNOWHOW без явного чтения потока метаданные потока не всегда доступны
        //read data chunk to determine stream meta data
        $buf = stream_get_contents($source_stream,$download_content_length);

        $this->current_content_length = min($download_content_length,strlen($buf));

        $stream_seekable = isset($stream_meta_data['seekable'])?$stream_meta_data['seekable']:false;

        $headers = array();
        //В зависимости от реализации обертки для http заголовки могут находиться в разных местах
        if(isset($stream_meta_data["wrapper_data"]["headers"])){
            $headers = $stream_meta_data["wrapper_data"]["headers"];
        }elseif(isset($stream_meta_data["wrapper_data"])){
            $headers = $stream_meta_data["wrapper_data"];
        }


        $header_matches = null;
        foreach($headers as $header){
            //ищем информацию о размере передаваемых данных
            if(preg_match('/content-length:\s*(\d+)/i',$header,$header_matches)){
                $this->content_length=intval($header_matches[1]);
                //и md5 хеше
            }elseif(preg_match('/content-md5:\s*([\da-f]{32})/i',$header,$header_matches)){
                $this->content_md5=$header_matches[1];
            }
        }


        if($buf && $this->download_stream){
            fwrite($this->download_stream,$buf);
        }
    }

    /**
     * "Настойчивое" открытие файла
     * на случай если ресурс занят другим процессом или директория еще не создана
     * @param $filename
     * @param $mode
     * @param $retry
     * @return resource
     */
    private function fopen($filename,$mode,$retry = 5)
    {
        $path = $this->root_path.$filename;
        if(!file_exists($path)){
            $this->mkdir(dirname($filename));
        }
        while(!($fp = fopen($path,$mode))){
            if(--$retry>0){
                sleep(1);
            }else{
                break;
            }
        }
        return $fp;
    }
}

class Playlist
{
    static $log_out;
    static $pls_id;
    static $dev;
    static $key_track;
    static $json_data;
    static $count_tracks;
    static $time_track;
    static $guid;

    public function __construct()
    {
        $this->time_start = time();
        require(path_updater . 'env.php');
        self::my_log(__CLASS__, __FUNCTION__, '------------------------START---------------------------', false);
    }

    public function __destruct()
    {
        self::my_log(__CLASS__, __FUNCTION__, '-------------------------END----------------------------', false);
    }

    /**
     * @return bool|mixed|string
     */
    static function getJsonPlaylist()
    {
        date_default_timezone_set(timezone);
        $params = [
            'dev' => self::$dev,
            'timezone' => date("e")
        ];
        $url = path.'/gms/playlist';

        $json_data = self::curlJsonResult($params, $url, 1);
        if ($json_data === FALSE) {
            self::my_log( __CLASS__, __FUNCTION__, ": Oшибка при получении данных от " . $url,  true);
            return false;
        }

        $json_data = json_decode($json_data);
        if ($json_data === FALSE) {
            self::my_log(__CLASS__, __FUNCTION__, ": Oшибка при получении JSON данных от " . $url, true);
            return false;
        } else {
            self::my_log(__CLASS__, __FUNCTION__, ": Успешно получены JSON данные: " . serialize($json_data));
        }

        if (empty($json_data->result)) {
            self::my_log(__CLASS__, __FUNCTION__, ': Ошибка, пустое обязательное значение JSON-данных, "result"!', true);
            return false;
        }

        if (!is_object($json_data->result)) {
            self::my_log(__CLASS__, __FUNCTION__, ': Ошибка, переменная "items" не является объектом!', true);
            return false;
        }

        return $json_data;
    }

    /**
     * @return int
     */
    private static function diffM3u ()
    {
        if ($update_at = self::getParamPlaylist("DATE"))
        {
            if (self::$json_data->pls->update_at == $update_at) {
                self::my_log(__CLASS__, __FUNCTION__, ': Плейлист ID:'.self::$pls_id.' не изменился!');
                return 1;
            } else {
                $datetime = date("Y-m-d H:i:s", self::$json_data->pls->update_at).':'.date("Y-m-d H:i:s", $update_at);
                self::my_log(__CLASS__, __FUNCTION__, ': Плейлист ID:'.self::$pls_id.' был изменен! ('.$datetime.')');
                return 2;
            }
        } else {
            self::my_log(__CLASS__, __FUNCTION__, ': Не удалось получить дату обновления плейлиста!', true);
            return 2;
        }
    }

    /**
     * @return string
     */
    private static function getUid()
    {
        return trim(shell_exec("cat /proc/cpuinfo | grep -i serial | awk '{print $3}'"));
    }

    /**
     * @param $name
     * @return bool
     */
    public static function getParamPlaylist($name)
    {
        $filePath = current.'/'.m3uPlaylist;

        if (file_exists($filePath))
        {
            $file_txt = file_get_contents($filePath);
            preg_match_all("/\s*#".$name.":(.*?)\r\n\s*/ies", $file_txt, $out_arr);

            if (!empty($out_arr[1][0])) return $out_arr[1][0];
            else {
                self::my_log(__CLASS__, __FUNCTION__, ": Не удалось получить " . $name . " плейлиста " . $filePath, false);
                return false;
            }
        } else {
            self::my_log(__CLASS__, __FUNCTION__, ": Не удалось найти файл " . $filePath);
            return false;
        }
    }

    /**
     * @param $name
     * @param $value
     * @return bool
     */
    public static function setParamPlaylist($name, $value)
    {
        $filePath = current.'/'.m3uPlaylist;

        if (file_exists($filePath))
        {
            try {
                $file_txt = file_get_contents($filePath);
                $replace = preg_replace("/\s*#".$name.":(.*?)\r\n\s*/is", "\r\n#".$name.":".$value."\r\n", $file_txt);
                if (file_put_contents($filePath, $replace)) {
                    return true;
                } else{
                    self::my_log(__CLASS__, __FUNCTION__, ": Не удалось задать параметр в плейлисте", true);
                    return false;
                }

            } catch (Exception $e) {
                self::my_log(__CLASS__, __FUNCTION__, ": Не удалось задать параметр в плейлисте ". $e, true);
                return false;
            }

        } else {
            self::my_log(__CLASS__, __FUNCTION__, ": Не удалось найти файл " . $filePath);
            return false;
        }
    }

    /**
     * @param $fileUrl
     * @param $saveTo
     * @return bool
     */
    private static function saveFile($fileUrl, $saveTo)
    {
        set_time_limit(0);
        if (file_exists($saveTo)) {
            self::my_log(__CLASS__, __FUNCTION__, ': Файл:' . $saveTo . ' уже существует!');
            //return false;
        }

        $fp = fopen($saveTo, 'w+');
        if($fp === false){
            self::my_log(__CLASS__, __FUNCTION__, ': Не удалось открыть: ' . $saveTo, true);
            return false;
        }

        $ch = curl_init($fileUrl);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_exec($ch);

        if (curl_errno($ch)){
            self::my_log(__CLASS__, __FUNCTION__, ": ".curl_error($ch), true);
            curl_close($ch);
            return false;
        }

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode != 200) {
            self::my_log(__CLASS__, __FUNCTION__, ": Код статуса: " . $statusCode, true);
            return false;
        } else {
            self::my_log(__CLASS__, __FUNCTION__, ': Файл:' . $saveTo . ' успешно загружен!');
            return true;
        }
    }

    /**
     *
     */
    public static function deleteTempFiles()
    {
        $error = [];
        $file_mp3 = current . '/' . m3uPlaylist;
        $file_inf = path_updater . jsonInfPlaylist;
        $file_edl = path_updater . edl_log;

        if (file_exists($file_mp3)) {
            if (!@unlink($file_mp3)) {
                $error[] = true;
                self::my_log(__CLASS__, __FUNCTION__, ": Не удалось удалить ".$file_mp3. "!", true);
            }
        }

        if (file_exists($file_inf)) {
            if (!@unlink($file_inf)) {
                $error[] = true;
                self::my_log(__CLASS__, __FUNCTION__, ": Не удалось удалить ".$file_inf. "!", true);
            }
        }

        if (file_exists($file_edl)) {
            if (!@unlink($file_edl)) {
                $error[] = true;
                self::my_log(__CLASS__, __FUNCTION__, ": Не удалось удалить ".$file_edl. "!", true);
            }
        }

        if (!in_array(true, $error)) {
            self::my_log(__CLASS__, __FUNCTION__, ": Успешно были удалены файлы " . basename($file_mp3) . ", " . basename($file_edl) . ", " . basename($file_mp3) . "!");
        }
    }

    /**
     * @return bool
     */
    public static function getCreateDataFromJson ()
    {
        if (!$pls_Json = Playlist::getJsonPlaylist())
            return 0;

        self::$json_data = $pls_Json->result;

        if (empty(self::$json_data->pls->id) || empty(self::$json_data->pls->files)) {
            self::my_log(__CLASS__, __FUNCTION__, ': Ошибка, пустое обязательное значение JSON-данных, id, files, children', true);
            return 0;
        }

        self::$pls_id = self::$json_data->pls->id;

        if (empty(self::$json_data->state)) {
            self::$pls_id = 0;
            return 3;
        }

        $currentPls = self::getParamPlaylist("ID");

        if ($currentPls && $currentPls == self::$pls_id) {
            self::my_log(__CLASS__, __FUNCTION__, ': Полученный плейлист ID:' . self::$pls_id . ' соотвествует активному!');
            return self::diffM3u();
        } else return 2;
    }

    /**
     * @param $state
     * @return int
     */
    public static function actionForState($state)
    {
        if (in_array($state, [0,2,3])) {
            //todo сохраняем историю о последнем видео
            self::stopAndEnd();
        }

        switch ($state)
        {
            //todo ошибка
            case 0:
                return 0;
                break;

            //todo плейлист получен но не изменился
            case 1:
                self::my_log(__CLASS__, __FUNCTION__, ': Действующий плейлист уже является актуальным!');
                return 1;
                break;

            //todo плейлист получен и изменился
            case 2:
                //todo скачиваем файлы с удаленного сервера
                if (!self::downloadMedia()) return 0;

                //todo перемещение файлов из временной папки
                if (!self::moveToCurrent()) return 0;

                //todo генерация плейлиста
                if (self::createPlaylistM3u()) {
                    self::my_log(__CLASS__, __FUNCTION__, ': Скрипт отработал без ошибок!');
                    return 2;
                } else return 0;
                break;

            //todo подходящих плейлистов для устройства нет
            case 3:
                self::my_log(__CLASS__, __FUNCTION__, ': Для устройства "' . self::$dev . '" на данный момент нет подходящих плейлистов!');
                return 3;
                break;

            default:
                return 0;
                break;
        }
    }

    /**
     * @param $class
     * @param $function
     * @param $string
     * @param bool $error
     */
    public static function my_log($class, $function, $string, $error = false)
    {
        date_default_timezone_set(timezone);
        $log_file_name = path_updater . "log.txt";
        $now = date("Y-m-d H:i:s P");

        if (file_exists($log_file_name) && (filesize($log_file_name) > 2 * 1048576)) {
            if (!@unlink($log_file_name)) {
                $class = __CLASS__;
                $function = __FUNCTION__;
                $string = ": Не удалось удалить ".$log_file_name. "!";
                $error = true;
            }
        }

        $error ? $color = 'red' : $color = 'green';
        self::$log_out[] = "<b>".$now."</b> <span style='color: $color;font-weight: bold'>".$function."</span> ".$string;
        file_put_contents($log_file_name, $now." ".$class.":".$function.$string."\r\n", FILE_APPEND);
    }

    /**
     * @param $params
     * @param $url
     * @param int $method
     * @return bool|mixed
     */
    private static function curlJsonResult($params, $url, $method = 1)
    {
        if ($curl = curl_init()) {
            if ($method == 0) {
                $url = $url.'?'.http_build_query($params);
            }
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            if ($method == 1) {
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
            }
            $json_data = curl_exec($curl);

            if (curl_errno($curl)){
                self::my_log(__CLASS__, __FUNCTION__, ": ".curl_error($curl), true);
                curl_close($curl);
                return false;
            }

            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($statusCode != 200) {
                self::my_log(__CLASS__, __FUNCTION__, ": Ошибка получения данных, код статуса: " . $statusCode, true);
                return false;
            }

            if (empty($json_data)) {
                self::my_log(__CLASS__, __FUNCTION__, ": Ошибка получения данных, пустые данные от " . $url, true);
                return false;
            }
        } else {
            self::my_log(__CLASS__, __FUNCTION__, ": Ошибка curl_init: " . $url, true);
            return false;
        }
        return $json_data;
    }

    /**
     * @return bool
     */
    private static function downloadMedia()
    {
        if (!is_array(self::$json_data->pls->files)) {
            self::my_log(__CLASS__, __FUNCTION__, ": Значение 'filesArray' должно иметь тип массив", true);
            return false;
        }

        foreach (self::$json_data->pls->files as $filename) {
            if (!file_exists(current. '/' . basename($filename))) {
                $pathFrom = backend_path . $filename;
                $pathTo = temp. '/' . basename($filename);
                if (!self::saveFile($pathFrom, $pathTo)) {
                    self::my_log(__CLASS__, __FUNCTION__, ": Ошибка при сохранении из '".$pathFrom."' в '".$pathTo."'", true);
                    return false;
                }
            } else {
                self::my_log(__CLASS__, __FUNCTION__, ": Файл ".basename($filename)." уже был загружен ранее!");
            }
        }

        self::my_log(__CLASS__, __FUNCTION__, ": Файлы были успешно загружены!");
        return true;
    }

    /**
     * @return bool
     */
    protected static function moveToCurrent()
    {
        foreach (self::folder(temp) as $file) {
            if (@!rename(temp . '/' . $file, current . '/' . $file)) {
                self::my_log(__CLASS__, __FUNCTION__, ": Ошибка при перемещении файла " . temp . '/' . $file . " из временной папки", true);
                return false;
            } else {
                self::my_log(__CLASS__, __FUNCTION__, ": Успешно был перемещен файл " . temp . '/' . $file . " из временной папки");
            }
        }
        return true;
    }

    /**
     * @param $path
     * @return array
     */
    private static function folder($path)
    {
        $files = [];
        foreach (scandir($path) as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }
            $files[] = $file;
        }
        return $files;
    }

    /**
     * @return bool
     */
    public static function createPlaylistM3u ()
    {
        $files = [];
        $arr_pls_inf = [];

        $filePath = current . '/' . m3uPlaylist;
        $json_inf = path_updater . jsonInfPlaylist;
        $log_edl = path_updater . edl_log;

        if (empty(self::$json_data->pls->id)
            || empty(self::$json_data->pls->m3u)
        ) {
            self::my_log(__CLASS__, __FUNCTION__, ": Пустое обязательное значение в plsJson", true);
            return false;
        }

        self::clearCache();

        $m3u = self::$json_data->pls->m3u;
        $update_at = self::$json_data->pls->update_at;

        $txt_body = '';
        $txt_head = "#EXTM3U";
        $txt_head .= "\r\n#ID:" . self::$pls_id;
        $txt_head .= "\r\n#DATE:" . $update_at;
        $txt_head .= "\r\n#GUID:";

        if (empty($m3u->title)
            || empty($m3u->children)
            || !is_array($m3u->children)
        ) {
            self::my_log(__CLASS__, __FUNCTION__, ": Пустое обязательное значение в m3u: " . $m3u, true);
            return false;
        }

        self::$count_tracks = count($m3u->children);
        self::my_log(__CLASS__, __FUNCTION__, ": Колличество проигрываемых видео в плейлисте - " . self::$count_tracks, false);

        $txt_head .= "\r\n#PLAYLIST:" . $m3u->title;

        if (file_exists($log_edl)) {
            if (!@unlink($log_edl)) {
                self::my_log(__CLASS__, __FUNCTION__, ": Не удалось удалить ".$log_edl. "!", true);
            }
        }

        foreach ($m3u->children as $children) {
            if (empty($children->title)
                || empty($children->key)
                || empty($children->file)
                || empty($children->duration)
                || empty($children->frame_rate)
                || empty($children->nb_frames)
                || !isset($children->start)
                || !isset($children->end)
            ) {
                self::my_log(__CLASS__, __FUNCTION__, ": Пустое обязательное значение в children: " . serialize($children), true);
                continue;
            }

            $files[$children->file][] = '';
            $index = count($files[$children->file]);

            $path_parts = pathinfo($children->file);
            $file_fragment = current."/" . $path_parts['filename'].'_fragment_'.$index.'.'.$path_parts['extension'];

            if (file_exists($file_fragment)) {
                if (!@unlink($file_fragment)) {
                    self::my_log(__CLASS__, __FUNCTION__, ": Не удалось удалить/обновить ссылку ".$file_fragment. "!", true);
                }
            }

            if (@!symlink(current."/".$children->file , $file_fragment )) {
                self::my_log(__CLASS__, __FUNCTION__, ": Не удалось создать символическую ссылку на файл " . current."/".$children->file, true);
                continue;
            }

            $path_parts = pathinfo($file_fragment);
            $file_edl = current . '/' . $path_parts["filename"] . '.txt';

            $eld_str = '';
            $create_eld = self::create_edl($file_edl, $children);
            if ($create_eld === FALSE) {
                self::my_log(__CLASS__, __FUNCTION__, "Ошибка создания EDL - файла: " . $file_edl, true);
            } elseif (!empty($create_eld)) {
                $eld_str = basename($file_edl);
            }

            $txt_body .= "\r\n#EXTINF:".$children->duration.",".$children->title;
            $txt_body .= "\r\n".$file_fragment;

            $arr_pls_inf[] = [
                'pls_id' => self::$pls_id,
                'device_id' => self::$dev,
                'key' => $children->key,
                'duration' => $children->duration,
                'type' => $children->type,
                'file_edl' => $eld_str,
                'file_fragment' => $file_fragment ? basename($file_fragment) : ''
            ];
        }

        if (!self::fileWrite($filePath, $txt_head.$txt_body, 'w')) {
            return false;
        }

        $json_pls_inf = json_encode($arr_pls_inf);
        if (!self::fileWrite($json_inf, $json_pls_inf, 'w')) {
            return false;
        }

        return true;
    }

    private static function clearCache()
    {
        //todo очистка кэша
        $error = [];
        $json_inf = path_updater . jsonInfPlaylist;

        if (file_exists($json_inf))
        {
            $file_json_data = file_get_contents($json_inf);
            $file_json_data = json_decode($file_json_data);

            foreach ($file_json_data as $info)
            {
                if (!empty($info->file_edl)) {
                    if (!@unlink(current . "/" . $info->file_edl)) {
                        $error[] = true;
                        self::my_log(__CLASS__, __FUNCTION__, ": Не удалось удалить файл EDL - " . $info->file_edl . "!", true);
                    }
                }

                if (!empty($info->file_fragment)) {
                    if (!@unlink(current . "/" . $info->file_fragment)) {
                        $error[] = true;
                        self::my_log(__CLASS__, __FUNCTION__, ": Не удалось удалить символическую ссылку - " . $info->file_fragment . "!", true);
                    }
                }
            }
        }

        if (!in_array(true, $error)) {
            self::my_log(__CLASS__, __FUNCTION__, ": Успешно был очищен кэш, удалены символические ссылки и EDL-файлы!");
        }
    }

    /**
     * @param $file
     * @param $text
     * @param string $mode
     * @return bool
     */
    private static function fileWrite($file, $text, $mode = 'w')
    {
        if (empty($text)) {
            self::my_log(__CLASS__, __FUNCTION__, ": Пустое значение данных", true);
            return false;
        }

        if (@!$handle = fopen($file, $mode)) {
            self::my_log(__CLASS__, __FUNCTION__, ": Ошибка при открытии файла " . $file, true);
            return false;
        }

        $fwrite = fwrite($handle, $text);
        fclose($handle);

        if ($fwrite === FALSE) {
            self::my_log(__CLASS__, __FUNCTION__, ": Не удалось сохранить файл: " . $file, true);
            return false;
        } else {
            self::my_log(__CLASS__, __FUNCTION__, ": Успешно был сгенерирован файл: " . $file);
            return true;
        }
    }

    /**
     * @param $file_edl
     * @param $data
     * @return bool|string
     */
    private static function create_edl($file_edl, $data)
    {
        $txt_body = '';
        $log_edl = path_updater . edl_log;

        $start_rate = round((int)$data->start * (int)$data->frame_rate);
        $end_rate = round((int)$data->end * (int)$data->frame_rate);
        $all_rate = (int)$data->nb_frames;

        $txt_head = "FILE PROCESSING COMPLETE " . $all_rate . " FRAMES AT " . (int)$data->frame_rate * 100;
        $txt_head .= "\r\n------------------------";

        if (empty($data->start)) {
            if ((int)$data->end == (int)$data->duration) {
                return '';
            } elseif ((int)$data->end < (int)$data->duration) {
                $txt_body = "\r\n" . $end_rate . ' ' . $all_rate;
            }
        } else {
            if ((int)$data->end == (int)$data->duration) {
                $txt_body = "\r\n" . "0 " . $start_rate;
            } elseif ((int)$data->end < (int)$data->duration) {
                $txt_body .= "\r\n". "0 " . $start_rate;
                $txt_body .= "\r\n" . $end_rate . ' '. $all_rate;
            }
        }
        $txt = "\r\n".$file_edl;
        $txt .= "\r\nstart:".$data->start.", end:".$data->end.", duration:".$data->duration.", frame_rate:".$data->frame_rate.", nb_frames:".$data->nb_frames;
        $txt .= "\r\n".$txt_head.$txt_body;
        $txt .= "\r\n+++++++++++++++++++++++++++++++++++++++++++";
        self::fileWrite($log_edl, $txt, 'a+');

        if (self::fileWrite($file_edl, $txt_head.$txt_body, 'w')) {
            return $file_edl;
        }

        return false;
    }

    /**
     * @return bool
     */
    static function stopAndEnd() {
        $arr_history = [
            'datetime' => time(),
            'guid' => self::getParamPlaylist("GUID"),
            'pls_id' => self::getParamPlaylist("ID"),
            'type_action' => 'stop'
        ];
        if (self::video_history_up((object)$arr_history)) {
            self::my_log(__CLASS__, __FUNCTION__, ': Успешно отправлена история о воспроизводимом видео!');
            return true;
        } else {
            self::my_log(__CLASS__, __FUNCTION__, ': Ошибка! Не удалось отправить историю о воспроизводимом видео!', true);
            return false;
        }
    }

    /**
     * @param $state
     */
    public static function shell_up($state)
    {
        //todo перезапуск контента на kodi
        $arr_commands = [];

        if (($state == 1 && self::getActivePlayer('video') === FALSE)
            || $state == 2) {
            $isDir = self::$count_tracks > 1 ? ',isdir' : '';
            $arr_commands = [
                'Action(stop)',
                'PlayMedia('.current.'/'.m3uPlaylist.$isDir.')',
                'PlayerControl(repeatall)',
                'Action(Fullscreen)',
                'ActivateWindow(12005)'
            ];
        } elseif (($state == 0 || $state == 3)
            && self::getActivePlayer('picture') === FALSE)
        {
            self::stopAndEnd();
            $arr_commands = [
                'Action(stop)',
                'ShowPicture('.current.'/'.logo_img.')',
            ];
        }

        if (!empty($arr_commands)) {
            foreach ($arr_commands as $command) {
                $command_txt = 'kodi-send -a "' . $command . '"';
                self::my_log(__CLASS__, __FUNCTION__, ": Выполнена команда: " . $command_txt);
                shell_exec($command_txt);
            }
        }
    }

    /**
     * @param $state
     */
    public static function history_up($state)
    {
        date_default_timezone_set(timezone);

        //todo запись в историю, отправка на сервер
        if (!isset(self::$pls_id) || empty(self::$log_out) || empty(self::$dev)) {
            self::my_log(__CLASS__, __FUNCTION__, ': Ошибка записи в историю, пустое или не определено значение: $pls_id, $log_out или $dev', true);
            return;
        }

        if ($state == 2 //плейлист изменился
            || ($state == 1 && self::getActivePlayer('video') === FALSE) //плейлист изменился и не воспроизводится
            || ($state == 3 && self::getActivePlayer('picture') === FALSE) //если смена на картинку
        ) {
            $arr_params = [
                'GmsHistory' => [
                    'pls_id' => self::$pls_id,
                    'device_id' => self::$dev,
                    'log_text' => serialize(self::$log_out),
                    'status' => (int)$state,
                    'created_at' => date("Y-m-d H:i:s P", time())
                ],
            ];

            if ($json_data = self::curlJsonResult($arr_params, path.'/gms/history', 1)) {
                $json_data = json_decode($json_data);
                if (empty($json_data->state)) {
                    self::my_log(__CLASS__, __FUNCTION__, ': На удаленном сервере произошла ошибка или запись данного события не предусмотрена!');
                    return;
                }
                self::my_log(__CLASS__, __FUNCTION__, ': История операций была успешно сохранена на удаленном сервере!');
            }
        }
    }

    /**
     * @param $sync
     * @return bool
     */
    public static function video_history_up($sync)
    {
        $inf = [];

        //todo запись в историю, отправка на сервер
        if (empty($sync->guid)
            || empty($sync->datetime)
            || empty($sync->type_action)) {
            self::my_log(__CLASS__, __FUNCTION__, ': Ошибка записи в историю, не определены обязательные параметры: '.serialize($sync), true);
            return false;
        }

        if (!$pls_id = Playlist::getParamPlaylist("ID"))
            return false;

        if ($sync->type_action == 'start' && !empty($sync->inf)) {
            $inf = [
                'key' => $sync->inf->key,
                'pls_pos' => $sync->inf->pls_pos,
                'duration' => $sync->inf->duration,
                'type' => $sync->inf->type,
            ];
        }

        $arr_params = [
            'guid' => $sync->guid,
            'datetime' => $sync->datetime,
            'type_action' => $sync->type_action,
            'device_id' => self::getUid(),
            'pls_id' => $pls_id,
            'inf' => $inf
        ];

        if ($json_data = self::curlJsonResult($arr_params, path.'/gms/video-history', 1)) {
            $json_data = json_decode($json_data);
            if (empty($json_data->state)) {
                self::my_log(__CLASS__, __FUNCTION__, ': На удаленном сервере произошла ошибка или запись данного события не предусмотрена!');
            } else return true;
        }

        return false;
    }

    /**
     * @param $type
     * @return bool|mixed
     */
    public static function getActivePlayer($type)
    {
        $playlist = [];
        $json = [
            'jsonrpc' => '2.0',
            'id' => '1',
            'method' => 'Player.GetActivePlayers'
        ];

        $params["request"] = json_encode($json);
        $result = self::curlJsonResult($params, jsonrpc, 0);

        if (empty($result)) {
            self::my_log(__CLASS__, __FUNCTION__, ": Oшибка при получении JSON данных от " . jsonrpc, true);
            return false;
        }

        $json_data = json_decode($result);

        if (!isset($json_data->result)) {
            self::my_log(__CLASS__, __FUNCTION__, ': Ошибка, пустое обязательное значение JSON-данных, "result"!', true);
            return false;
        }

        if (!is_array($json_data->result)) {
            self::my_log(__CLASS__, __FUNCTION__, ': Ошибка, переменная "result" не является массивом!', true);
            return false;
        }

        foreach ($json_data->result as $item) {
            $playlist[$item->type] = (int)$item->playerid;
        }

        if ($playlist && array_key_exists($type, $playlist)) {
            return $playlist[$type];
        }

        return false;
    }

    /**
     * @return bool|mixed
     */
    public static function getCurrentPos()
    {
        $kodi_player_current = self::getActivePlayer("video");
        if ($kodi_player_current === FALSE)
            return false;

        $json = [
            'jsonrpc' => '2.0',
            'id' => '1',
            'method' => 'Player.GetProperties',
            'params' => [
                'playerid' => $kodi_player_current,
                'properties' => [
                    "time",
                    "position"
                ]
            ]
        ];

        $params["request"] = json_encode($json);
        $result = self::curlJsonResult($params, jsonrpc, 0);

        if (empty($result)) {
            self::my_log(__CLASS__, __FUNCTION__, ": Oшибка при получении JSON данных от " . jsonrpc, true);
            return false;
        }

        $json_data = json_decode($result);

        if (!isset($json_data->result->position)) {
            self::my_log(__CLASS__, __FUNCTION__, ': Ошибка! Не удалось позицию играющего видео!', true);
            return false;
        } else {
            self::$key_track = (int)$json_data->result->position;
            self::$time_track = $json_data->result->time;
            return true;
        }
    }

    /**
     * @param $pos
     * @return bool
     */
    public static function getCurrentInf($pos)
    {
        $json_inf = path_updater . jsonInfPlaylist;
        $txt_json = file_get_contents($json_inf);
        $txt_json = json_decode($txt_json);
        if (!empty($txt_json[$pos]))
            return $txt_json[$pos];

        return false;
    }

    public function sync()
    {
        self::$dev = self::getUid();
        $state = self::getCreateDataFromJson();
        $state = self::actionForState($state);
        self::shell_up($state);
        self::history_up($state);
        if ($state == 0 || $state == 3) {
            self::clearCache();
            self::deleteTempFiles();
        }
    }
}

$Playlist = new Playlist();
if ($argv[1] == 'sync') {
    $Playlist->sync();
}
?>