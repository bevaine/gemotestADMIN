<?php

namespace app\modules\admin\controllers;

use common\models\nkkm;
use Yii;
use common\models\NKkmUsers;
use common\models\NKkmUsersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KkmUsersController implements the CRUD actions for NKkmUsers model.
 */
class KkmUsersController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all NKkmUsers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NKkmUsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single NKkmUsers model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new NKkmUsers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new NKkmUsers();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing NKkmUsers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing NKkmUsers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionExport($id)
    {
        header('Content-Type: application/xml; charset=utf-8');
        header('Content-Disposition: attachment; filename="settings.xml"');
        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_HTML;

        if ($findModel = $this->findModel($id))
        {
            $sender_key = $findModel->kkm->sender_key;
            $kkm = $findModel->kkm->number;
            $searchModel = new NKkmUsersSearch();
            if ($sender_key == 'Физ. мс выездная') {
                $search['NKkmUsersSearch']['sender_key'] = 'Физ. мс выездная';
            } elseif ($sender_key == 'Физ.Врач выездной') {
                $search['NKkmUsersSearch']['sender_key'] = 'Физ.Врач выездной';
            } else return null;

            $xml_logins = '';
            $dataProvider = $searchModel->search($search);
            foreach ($dataProvider->getModels() as $model) {
                /** @var NKkmUsers $model */
                $fio = $model->logins->Name;
                if (!empty($model->logins->operators->fio)) {
                    $fio = $model->logins->operators->fio;
                }
                $xml_logins .= "\r\n".<<<XML
                    <seller name="{$fio}" login="{$model->login}" mode="K" mask="x0001" pass="{$model->password}"/>
XML;
            }

            $xml = <<<XML
                <!-- Настройки программы courier -->
                <!-- F:\NilstarSoft\4-АИПЫ И МАЙНАПЫ\Gemotest -->
                <courier dt="2018-04-13 01:00:05">
                  <menu login="xFFFF" name="РЕЖИМЫ РАБОТЫ">
                    <menu login="1" image="delivery.b16" name="РАБОТА С ЗНД" func="Seller_Start" />
                    <menu login="1" image="kassa.b16" name="РАБОТА С ЧЕКАМИ" func="Seller_UserStart" />
                    <menu login="1" image="addition.b16" name="ДОПОЛНИТЕЛЬНО">
                      <!-- LOGIN: кассир/экспeдитор -->
                      <menu login="x00FE" image="zreport.b16" name="Z-ОТЧЕТ" func="Seller_ZReport" />
                      <menu image="xreport.b16" name="X-ОТЧЕТ" func="Reports_XReport" />
                      <menu image="server_load.b16" name="ОБМЕН С ОФД" func="OFD_Transfer" />
                      <menu image="sber.b16" name="СВЕРКА ИТОГОВ" func="CardModule_MakeTotalMenu" />
                      <menu image="cashbox.b16" name="ДЕНЕЖНЫЙ ЯЩИК">
                        <menu image="cashbox_how.b16" name="СУММА В ККМ" func="Seller_CashBoxSum" />
                        <menu image="cashbox_out.b16" name="ВЫПЛАТА" func="Seller_CashBoxOut" />
                        <menu image="cashbox_in.b16" name="ВНЕСЕНИЕ" func="Seller_CashBoxIn" />
                      </menu>
                      <menu image="report.b16" name="ЭЛЕКТР. ЖУРНАЛ">
                        <menu image="report.b16" name="ОТЧЕТ ПО СМЕНЕ" func="EJ_ShiftReport" />
                        <menu name="ДОКУМЕНТ ПО НОМЕРУ" func="EJ_NoDoc" />
                        <menu image="report_time.b16" name="ОТЧЕТ ПО ВРЕМЕНИ" func="EJ_TimeReport" />
                        <menu image="report_num.b16" name="ОТЧЕТ ПО НОМЕРАМ" func="EJ_NoReport" />
                      </menu>
                    </menu>
                    <menu login="1" image="load.b16" name="ЗАГРУЗКА/ВЫГРУЗ." func="Client_Menu" />
                    <menu image="order_list.b16" name="ИНФ О МАРШ.ЛИСТЕ" func="Seller_OrderListInfo" />
                    <menu login="x00FE" image="service.b16" name="СЛУЖЕБНЫЕ ФУНК.">
                      <!-- LOGIN: администратор -->
                      <menu image="clock.b16" name="УСТ.ДАТЫ/ВРЕМЕНИ" func="FR_SetDateTime" /> <!-- попробовать заблокировать -->
                      <menu image="inspektor.b16" name="СЕРВИС ФИСК.РЕГ" func="FR_InspectorService" /> <!-- попробовать заблокировать -->
                      <menu image="bank.b16" name="СЕРВИС БАНКА" func="CardModule_Service" /> <!-- попробовать заблокировать -->
                      <menu image="connect.b16" name="КОММУНИКАЦИИ" func="Conn_SetParam" /> <!-- попробовать заблокировать -->
                      <menu image="license.b16" name="ЛИЦЕНЗИЯ" func="Common_LicInfo" /> <!-- попробовать заблокировать -->
                      <menu image="clean.b16" name="ОЧИСТКА ККМ" func="Seller_Clean" /> <!-- попробовать заблокировать -->
                      <menu name="ОЧИСТКА ЭЛ.ЖУРНАЛА" func="EJ_Clear" /> <!-- попробовать заблокировать -->
                    </menu>
                    <menu image="info.b16" name="ИНФОРМАЦИЯ" func="Info_View" />
                    <!--<menu image="register.b16" name="РЕГИСТРАЦИЯ" func="Seller_Register" />-->
                  </menu>
                
                  <terminal>
                    <!-- Здесь описываются переменные системы PROLIN терминала.
                         Формат: <[какоето имя] var="[имя переменной в PROLIN]" value="[значение]" />.
                         Переменные инициализируются при старте приложения. -->
                    <!-- Время отключения экрана (0 - не отключать).  -->
                    <par var="persist.sys.backlighttime" value="5" />
                    <!-- Время перехода от отключения экрана в спячку (0 - не переходить).-->
                    <par var="persist.sys.sleeptime" value="5" />
                    <!-- Время от предупреждения до перехода в спячку. -->
                    <par var="persist.sys.sleepwaiting" value="0" />
                    <par var="persist.sys.sound.enable" value="1" />
                    <par var="persist.sys.sound.volume" value="30" />
                    <par var="persist.sys.key.backlight" value="0" />
                    <par var="persist.sys.lcd.brightness" value="8" />
                  </terminal>
                
                  <auth link="auth.xml"> <!-- Необходимо ввести все логины/пароли выездных медсестёр в роли Администратор -->
                    <!-- Системный администратор ( login:9999 pass:9999 ) -->
                    <sys name="Системный администратор" hash="3A113FDADB478B0A4183C62DD856A511E6C37554" type="SHA1" mode="S" mask="xFFFF"/>
                    <!-- Администратор ( login:0000 pass:0000 ) -->
                    <adm name="Администратор" login="0000" hash="92AAAB09CA2412A82A2FBF5CDC2DB340B9A3FA79" type="SHA1" mode="A" mask="x00FF"/>{$xml_logins}
                  </auth>
                
                  <shift>
                    <!-- Параметры передаваемые фискальному модулю, перед открытием смены. -->
                    <!-- Параметры вида документов
                         (Используется в команде фискального модуля [код 4С]):
                         param1:
                         - не печатать нулевые счетчики (флаг x0004);
                         - не печатать информацию о ресурсах (флаг x0020);
                         - не печатать поле "Количество" в команде "Оформление позиции товара/услуги", если оно равно 1 (флаг x0040);
                         - печать налога в каждой товарной позиции чека (флаг x8000).
                         param2:
                         - автоматическая инкассация при закрытии смены (флаг x0001). -->
                    <params param1="x8064" param2="0" linefeed="0" />
                
                    <!-- Настройки режима продавца.
                         modeopenshift: 0 - открывать смену при входе в режим работы с ЗНД, 1 - открывать смену при первом чеке.
                         useshiftnumber: использовать номер смены в отчетах.
                         lowchargelevel: 0..50 - значение уровня заряда меньше которого выдается предупреждение при попытке провести операцию кассиром.
                         flagcardtotal: 0 - сверка итогов при открытии и закрытии смены (по умолчанию), 1 - при открытии смены, 2 - при закрытии смены.
                         usegoodlist: 0 - не использовать список дополнительного товара, 1 - использовать список дополнительного товара (goodlist.xml).
                         salecopies: кол-во дополнительных копий для чека продажи.
                         bankcopies: кол-во дополнительных копий банковского слипа.
                         taxsumenable: 0: не выводить суммы налогов по позиции, 1: выводить суммы налогов по позиции в отчет. -->
                    <settings modeopenshift="1" useshiftnumber="1" usegoodlist="1" addgoodmode="1" lowchargelevel="10" flagcardtotal="2" salecopies="0" bankcopies="1" taxsumenable="1" />
                
                    <!-- Настройки обработки ЗНД и данные по умолчанию.
                         client: имя клиента по умолчанию для печати на чеке;
                         tname: имя позиции для обобщенного чека. -->
                    <orderparams client="Розничный покупатель">
                      <!-- Справочник отказов товара (по нему строится меню для выбора причины отказа).
                           deficit: код причины, указаваемый при недосдаче. -->
                      <cancelreasons menuname="ПРИЧИНА ОТКАЗА" deficit="0">
                        <reason type="0" name="НЕДОСДАЧА"/>
                        <reason type="1" name="ОТКАЗ ПОКУПАТЕЛЯ"/>
                        <reason type="2" name="НЕ ТОВАРНЫЙ ВИД"/>
                        <reason type="3" name="НЕ ТОТ РАЗМЕР"/>
                        <reason type="4" name="НЕ ТОТ ЦВЕТ"/>
                        <reason type="5" name="ПРОЧЕЕ"/>
                      </cancelreasons>
                      <!-- Справочник отмен ЗНД (по нему строится меню для выбора причины отмены ЗНД).
                           zreport: код причины для отмены при закрытии смены. -->
                      <cancelorder menuname="ПРИЧИНА ОТМЕНЫ" zreport="00" >
                        <!-- type: причина отмены;
                             name: название причины отмены (для отображения в меню). -->
                        <reason type="01" name="ДОСТАВКА ОТЛОЖЕНА"/>
                        <reason type="02" name="ОТКАЗ ОТ ДОСТАВКИ"/>
                        <reason type="03" name="ЗНД НЕТ В МАШИНЕ"/>
                      </cancelorder>
                    </orderparams>
                
                    <!-- Параметры видов платежей
                         Используется фискальным регистратором [код 4A]. -->
                    <payments>
                      <payment index="2" secondline="0" returnchange="0" currencyindex="0" maskofoper="x07" exchangecourse="1.00" name="КАРТА" />
                      <!-- <payment index="15" secondline="0" returnchange="0" currencyindex="0" maskofoper="x07" exchangecourse="1.00" name="Карта-кошелек" /> -->
                    </payments>
                
                    <!-- Параметры таблицы налогов для соответствия со внешней системой (taxesfn)
                         index: индекс налоговой ставки, используемой в ФР (число);
                         id: идентификатор налоговой ставки, используемой во внешней системе (число/строка до 7 символов). -->
                    <taxesfn>
                      <!-- НАЛОГ: "БЕЗ НДС" -->
                      <tax index="0" id="0" />
                      <!-- НАЛОГ: "НДС 10%" -->
                      <tax index="4" id="1" />
                      <!-- НАЛОГ: "НДС 18%" -->
                      <tax index="5" id="2" />
                      <!-- НАЛОГ: "НДС 0%" -->
                      <tax index="1" id="3" />
                    </taxesfn>
                
                    <totaldialog>
                      <!-- Настойка диалога "РАСЧЕТ"
                           Поля item: способы оплаты и их соответствие фискальным способам оплаты,
                           задаются в том же порядке как они будут отображаться в меню "РАСЧЕТ".
                           Параметры пунктов меню item:
                           fiscalindex - индекс фискального способа оплаты (0-наличные, 1-кредит, 2-карта, >2-пользовательские), xFF-аннулировать чек;
                           typeofpayment - способ оплаты, т.е. процедура которая используется при оплате (0-аннулировать чек, 1-ввод суммы со сдачей, 2-карта, 3-ввод суммы не более запрошенной);
                           name - имя пункта (если не задано, берется из фискального модуля, кроме случая typeofpayment == 0 или fiscalindex == xFF). -->
                      <item fiscalindex="0" typeofpayment="1" name="НАЛИЧНЫЕ" />
                      <item fiscalindex="2" typeofpayment="2" name="КАРТА" />
                      <item fiscalindex="xFF" name="АННУЛИРОВАТЬ ЧЕК" />
                    </totaldialog>
                
                    <!-- Настройки работы с ОФД:
                         name: имя соединения (из раздела connections) для работы с ОФД в режиме работы с ЗНД;
                         mode: работа с ОФД
                               0 - не использовать работу с ОФД в режиме продавца;
                               1 - работа с ОФД после пробития чека, коммуникационный модуль включается при входе в режим РАБОТА С ЗНД;
                               2 - фоновая работа с ОФД по периоду, коммуникационный модуль включается при входе в режим РАБОТА С ЗНД;
                         cardstop:
                               0 - не освобождать коммуникационный модуль при входе в банковский модуль.
                               1 - освобождать коммуникационный модуль при входе в банковский модуль.
                         Параметры таймаутов для работы с ОФД (не сохраняются в ФР, действуют только для программы):
                         period: период обращения к ОФД (в секундах).
                         sendtime: время отправки пакета в ОФД (в секундах).
                         recvtime: время ожидания ответа от ОФД (в секундах). -->
                    <ofd name="MegaFon" mode="2" cardstop="1" period="300" sendtime="15" recvtime="60" />
                  </shift>
                
                  <common>
                    <!-- Общие настройки программы. -->
                    <!-- workdir: имя рабочего каталога для хранения файлов маршрутного листа. -->
                    <workdir>work</workdir>
                    <!-- frserialpattern: шаблон (как в функции printf) для вывода серийного номера ФР в обменных файлах. -->
                    <frserialpattern>%S</frserialpattern>
                    <!-- timefmt: шаблон (как в функции strftime) для вывода времени в обменных файлах (st1,st3). -->
                    <timefmt>%F %T</timefmt>
                  </common>
                
                  <connections>
                    <conn name="WIFI" type="WIFI" apn="IRAS" pass="" dhcp="1" timeout="45" />
                    <conn name="Megafon" type="GPRS" apn="internet.megafon.ru" login="megafon" pass="megafon" timeout="60" />
                    <!--     <conn name="MTS" type="GPRS" apn="internet.mts.ru" login="mts" pass="mts" timeout="60" /> -->
                    <!--     <conn name="TELE2" type="GPRS" apn="internet.tele2.ru" timeout="60" /> -->
                    <conn name="USB-ETH" type="ETH1" dhcp="1" timeout="45" />
                  </connections>
                
                  <!-- Настройка клиента (меню ЗАГРУЗКА/ВЫГРУЗ) атрибуты:
                       updateapp_disable="1": блокирует возможность обновления приложения (не появляется пункт меню ОБНОВЛ. ПРИЛОЖЕНИЯ);
                       updateos_disable="1": блокирует возможность обновления ОС (не появляется пункт меню ОБНОВЛ.ОПЕР.СИСТЕМЫ);
                       updatelic_disable="1": блокирует возможность обновления лицензии (не появляется пункт меню ЗАГР. ЛИЦЕНЗИИ);
                       goodlist_name="<filename>": имя файла списка дополнительного товара (по умолчанию "goodlist.xml");
                       config_name="<filename>": имя файла настроек для загрузки (по умолчанию "settings.xml");
                       auth_name="<filename>": имя файла реестра пользователей для загрузки (по умолчанию "auth.xml");
                       os_name="<filename>": имя файла ОС для загрузки (по умолчанию "prolin-iras.zip"); -->
                  <client>
                    <!--<loadorderlist url="ftp://192.168.43.177/uploads/IN" login="admin" pass="12345678" timeout="300" period="2" arch="../ARH/IN" />
                    <loadorder url="ftp://192.168.43.177/uploads/IN" login="admin" pass="12345678" timeout="300" period="2" arch="../ARH/IN" />
                    <uploadorderpacket url="ftp://192.168.43.177/uploads/OUT" login="admin" pass="12345678" timeout="300" period="2" />
                    <checkaccess url="ftp://192.168.43.177/uploads/IN" login="admin" pass="12345678" timeout="300" period="2" />
                    <updateapp url="ftp://192.168.43.177/uploads/PROG" login="admin" pass="12345678" />
                    <updateconfig url="ftp://192.168.43.177/uploads/CONFIG" login="admin" pass="12345678" />
                    <updatelic url="ftp://192.168.43.177/uploads/CONFIG" login="admin" pass="12345678" />
                    <updatecert url="ftp://192.168.43.177/uploads/CONFIG" login="admin" pass="12345678" />
                    <updateos url="ftp://192.168.43.177/uploads/PROG" login="admin" pass="12345678" />-->
                
                    <!--<loadorderlist url="ftp://127.0.0.1/uploads/IN" login="admin" pass="12345678" arch="../ARH/IN" />
                    <loadorder url="ftp://127.0.0.1/uploads/IN" login="admin" pass="12345678" arch="../ARH/IN" />
                    <uploadorderpacket url="ftp://127.0.0.1/uploads/OUT" login="admin" pass="12345678" />
                    <loadgoodlist url="ftp://127.0.0.1/uploads/GOOD" login="admin" pass="12345678" />
                    <checkaccess url="ftp://127.0.0.1/uploads/IN" login="admin" pass="12345678" timeout="300" period="2" />
                    <updateapp url="ftp://192.168.1.13/uploads/PROG" login="admin" pass="12345678" />
                    <updateconfig url="ftp://192.168.1.13/uploads/CONFIG" login="admin" pass="12345678" />
                    <updatelic url="ftp://192.168.1.13/uploads/CONFIG" login="admin" pass="12345678" />
                    <updatecert url="ftp://192.168.1.13/uploads/CONFIG" login="admin" pass="12345678" />
                    <updateos url="ftp://192.168.1.13/uploads/PROG" login="admin" pass="12345678" />-->
                
                    <loadorderlist url="https://test-api2.gemotest.ru/exchange/iras/loadorderlist/num{$kkm}" order_path="loadorder" goodlist_path="loadgoodlist" login="" pass="" ca="ca.p7b"/>
                    <loadgoodlist url="https://test-api2.gemotest.ru/exchange/iras/loadgoodlist/num{$kkm}" login="" pass="" ca="ca.p7b"/>
                    <loadorder url="https://test-api2.gemotest.ru/exchange/iras/loadorder/num{$kkm}" login="" pass="" ca="ca.p7b"/>
                    <uploadorder url="https://test-api2.gemotest.ru/exchange/iras/uploadorder/num{$kkm}" order_path="check" shift_path="shift" log_path="log" login="" pass="" ca="ca.p7b"/>
                    <uploadorderpacket url="https://test-api2.gemotest.ru/exchange/iras/uploadorderpacket/num{$kkm}" login="" pass="" ca="ca.p7b"/>
                    <updateapp url="https://test-api2.gemotest.ru/exchange/iras/updateapp/num{$kkm}" login="" pass="" ca="ca.p7b"/>
                    <updateconfig url="https://test-api2.gemotest.ru/exchange/iras/updateconfig/num{$kkm}" login="" pass="" ca="ca.p7b"/>
                    <updateauth url="https://test-api2.gemotest.ru/exchange/iras/updateauth/num{$kkm}" login="" pass="" ca="ca.p7b"/>
                    <updatelic url="https://test-api2.gemotest.ru/exchange/iras/updatelicense/num{$kkm}" ca="ca.p7b"/>
                    <updatecert url="https://test-api2.gemotest.ru/exchange/iras/updatecertificate/num{$kkm}" login="" pass="" ca="ca.p7b"/>
                    <updateos url="https://test-api2.gemotest.ru/exchange/iras/updateos/num{$kkm}" login="" pass="" ca="ca.p7b"/>
                  </client>
                </courier>
XML;
            return $xml;
        }
        return null;
    }



    /**
     * Finds the NKkmUsers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NKkmUsers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NKkmUsers::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
