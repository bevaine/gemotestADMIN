<?php
const version = 1.04;
const timezone = 'Europe/Moscow';

const logo_img = 'logo.jpg';
const edl_log = 'edl_log.txt';
const m3uPlaylist = 'playlist.m3u';
const jsonInfPlaylist = 'playlist_inf.json';

const backend_path = 'https://corptv.gemotest.ru/';
const path = backend_path . 'api';
const jsonrpc = "http://127.0.0.1:8080/jsonrpc";

const current = '/storage/videos';
const temp = '/storage/downloads';
const path_updater = '/storage/.kodi/userdata/updater/';
#-------------------------------------------------------

if ($argv[1] == 'sync')
{
    $Playlist = new Playlist();
    $Playlist->sync();
}
elseif ($argv[1] == 'start' || $argv[1] == 'stop') {
    $SyncHistory = new SyncHistory();
    $SyncHistory->type_action = $argv[1];
    $SyncHistory->sync();
}
elseif ($argv[1] == 'update')
{
    header("Content-type: text/html; charset=utf-8");
    $SyncUpdate = new SyncUpdate();
    if ($SyncUpdate->update()) {
        $msg = ' Успешно было выполнено обновление с версии ' . $SyncUpdate->version_local.' на ' . $SyncUpdate->version_remote;
        Playlist::my_log('', '', $msg, false);
    } else {
        $msg = ' Не удалось выполнить обновление версии с ' . $SyncUpdate->version_local.' на ' . $SyncUpdate->version_remote;
        Playlist::my_log('', '', $msg, true);
    }
}

try {
    $action = isset($_GET['action']) ? $_GET['action'] : false;
    switch ($action) {
        //todo запрос версии
        case 'version':
            header("Content-type: application/json; charset=utf-8");
            echo json_encode(['version' => (int)str_replace('.', '', version)]);
            break;
        //todo скачивание файла
        case 'update':
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($_SERVER['SCRIPT_NAME']));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize(basename($_SERVER['SCRIPT_NAME'])));
            readfile(basename($_SERVER['SCRIPT_NAME']));
            break;
    }
} catch(Exception $ex){
    $msg = 'Возникла ошибка при обновлении скрипта: ' . $ex->getCode().' - ' . $ex->getMessage();
    Playlist::my_log(__CLASS__, __FUNCTION__, $msg, true);
}

class SyncUpdate
{
    public $script_name;
    public $version_local;
    public $version_remote;
    public $source_path;
    public $download_path;
    public $backup_path;
    public $backup_script;

    /**
     * SyncUpdate constructor.
     */
    public function __construct()
    {
        $this->version_local = (int)str_replace('.', '', version);
    }

    /**
     * @return bool
     */
    public function update()
    {
        $this->script_name =  basename($_SERVER['SCRIPT_NAME']);
        $this->source_path = backend_path . 'update/'. $this->script_name . '?action=update';
        $this->backup_script = pathinfo($this->script_name)['filename'] . '_v'.$this->version_local . '.php';

        $this->backup_path = path_updater . 'backup/';
        $this->download_path = path_updater . 'download/';

        $version_json = Playlist::curlJsonResult([
            'action' => 'version'
        ], backend_path . 'update/'. $this->script_name, $method = 0);

        if (!$version_json) return false;
        $version = json_decode($version_json);
        if (isset($version->version)) {
            $this->version_remote = $version->version;
        } else {
            Playlist::my_log(__CLASS__, __FUNCTION__, ': Ошибка! Не удалось получить версию скрипта на удаленном сервере', true);
            return false;
        }

        if ($this->version_remote > $this->version_local)
        {
            return $this->backup();
        } else {
            $msg = ': Версия скрипта ' . version . ' является актуальной, обновление не требуется';
            Playlist::my_log(__CLASS__, __FUNCTION__, $msg, true);
            return false;
        }
    }

    public function backup()
    {
        try {
            //todo создание папок, если нет
            if (!is_dir($this->backup_path) &&
                @!mkdir($this->backup_path, 0777, true)) {
                $msg = ': Ошибка! Не удалось создать директорию ' . $this->backup_path;
                Playlist::my_log(__CLASS__, __FUNCTION__,$msg , true);
                return false;
            }
            if (!is_dir($this->download_path) &&
                @!mkdir($this->download_path, 0777, true)) {
                $msg = ': Ошибка! Не удалось создать директорию ' . $this->download_path;
                Playlist::my_log(__CLASS__, __FUNCTION__,$msg , true);
                return false;
            }

            //todo сохраняем новую версию скрипта в временную папку
            if (!Playlist::saveFile($this->source_path, $this->download_path . $this->script_name  )) {
                $msg = ': Ошибка! Не удалось сохранить из '. $this->source_path . ' в ' . $this->download_path . $this->script_name;
                Playlist::my_log(__CLASS__, __FUNCTION__, $msg, true);
                return false;
            }

            //todo копируем старую версию скрипта в backup
            if (!file_exists(path_updater . $this->script_name)
                || @!copy(path_updater . $this->script_name, $this->backup_path . $this->backup_script)) {
                $msg = ': Ошибка! Не удалось скопировать из '. path_updater . $this->script_name . ' в ' . $this->backup_path . $this->backup_script;
                Playlist::my_log(__CLASS__, __FUNCTION__, $msg, true);
                return false;
            }

            //todo заменяем старую версию скрипта на новую
            if (!file_exists($this->download_path . $this->script_name  )
                || @!rename($this->download_path . $this->script_name  , path_updater . $this->script_name)) {
                $msg = ': Ошибка! Не удалось заменить файл '. $this->download_path . $this->script_name . ' на ' . path_updater . $this->script_name;
                Playlist::my_log(__CLASS__, __FUNCTION__, $msg, true);
                return false;
            }

        } catch(Exception $ex){
            $msg = 'Возникла ошибка при обновлении скрипта: '.$ex->getCode().' - '.$ex->getMessage();
            Playlist::my_log(__CLASS__, __FUNCTION__, $msg, true);
            return false;
        }

        return true;
    }
}

class SyncHistory
{
    public $type_action;
    public $guid;
    public $inf;

    public function __construct()
    {
        $this->datetime = time();
        Playlist::my_log(__CLASS__, __FUNCTION__, '------------------------START---------------------------', false);
    }

    public function __destruct()
    {
        Playlist::my_log(__CLASS__, __FUNCTION__, '-------------------------END----------------------------', false);
    }

    /**
     * @return bool
     */
    public function setCurrentPlay()
    {
        //определение позиции и времени играющего видео
        if ($this->type_action == 'start')
        {
            if (Playlist::getCurrentPos() === FALSE)
                return false;

            //получение информации о играющем видео
            /** @var mixed $inf */
            $this->inf = Playlist::getCurrentInf(Playlist::$key_track);
            if ($this->inf === FALSE)
                return false;

            $this->inf->pls_pos = Playlist::$key_track;

            if (Playlist::$key_track == 0)
            {
                //генерация и запись guid в заголовок плейлиста
                $this->guid = uniqid(time(), true);
                if (Playlist::setParamPlaylist("GUID", $this->guid))
                {
                    Playlist::my_log(__CLASS__, __FUNCTION__, ': Сгенерирован новый GUID плейлиста - ' . $this->guid, false);
                } else {
                    Playlist::my_log(__CLASS__, __FUNCTION__, ': Ошибка! Не удалось сгенерировать новый GUID плейлиста - ' . $this->guid, false);
                }
            }
        }

        if (empty($this->guid)) {
            $this->guid = Playlist::getParamPlaylist("GUID");
        }

        if (Playlist::video_history_up($this)) {
            Playlist::my_log(__CLASS__, __FUNCTION__, ': Успешно были сохранена история о воспроизводимом видео', false);
            return true;
        }
        return false;
    }

    public function sync()
    {
        self::setCurrentPlay();
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
        self::my_log(__CLASS__, __FUNCTION__, '------------------------START---------------------------', false);
    }

    public function __destruct()
    {
        self::my_log(__CLASS__, __FUNCTION__, '-------------------------END----------------------------', false);
    }

    static function get_ip_address() {
        return trim(shell_exec("ifconfig eth0 |grep \"inet addr:\"|cut -f 2 -d ':'|cut -f 1 -d ' '"));
    }

    /**
     * @return bool|mixed|string
     */
    static function getJsonPlaylist()
    {
        date_default_timezone_set(timezone);
        $params = [
            'IP' => self::get_ip_address(),
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
    public static function saveFile($fileUrl, $saveTo)
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

        if (empty(self::$json_data->state)) {
            self::$pls_id = 0;
            return 3;
        }

        if (empty(self::$json_data->pls->id) || empty(self::$json_data->pls->files)) {
            self::my_log(__CLASS__, __FUNCTION__, ': Ошибка, пустое обязательное значение JSON-данных, id, files', true);
            return 0;
        }

        self::$pls_id = self::$json_data->pls->id;

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
        if (in_array($state, [0,2,3])
            && self::getActivePlayer('video') === TRUE) {
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
        $handle = fopen($log_file_name, "a");
        if ($handle) {
            fwrite($handle, $now." ".$class.":".$function.$string."\r\n");
            fclose($handle);
        }
    }

    /**
     * @param $params
     * @param $url
     * @param int $method
     * @return bool|mixed
     */
    public static function curlJsonResult($params, $url, $method = 1)
    {
        if ($curl = curl_init()) {
            if ($method == 0) {
                $url = $url.'?'.http_build_query($params);
            }
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
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
    public static function moveToCurrent()
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

        if (empty($json_data->result)) {
            self::my_log(__CLASS__, __FUNCTION__, ': Ошибка, пустое значение "result". В данный момент нет активных плейров!', true);
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
?>