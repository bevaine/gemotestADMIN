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
        $response = Yii::$app->response;
        $response->format = yii\web\Response::FORMAT_HTML;

        $html_error = <<<HTML
<p>Ошибка! %s</p>
HTML;

        if (!$findModel = $this->findModel($id))
            return printf($html_error, 'Не удалось найти по id: '.$id);

        if (empty($findModel->kkm->sender_key)
            || empty($findModel->kkm->number)
        ) return printf($html_error, 'Отсуствует обязательный параметр sender_key или number');

        $sender_key = $findModel->kkm->sender_key;
        $kkm = $findModel->kkm->number;

        if (!empty($sender_key)) {
            $s = NKkmUsers::find()
                ->joinWith(['kkm'])
                ->where(['sender_key' => $sender_key])
                ->all();
        } else
            return printf($html_error, 'Пустой sender_key');

        $xml_logins = '';
        foreach ($s as $model) {
            /** @var NKkmUsers $model */
            $fio = $model->logins->Name;
            if (!empty($model->erpUsers->fio)) {
                $fio = $model->erpUsers->fio;
            }
            $xml_logins .= "\r\n".<<<XML
    <seller name="{$fio}" login="{$model->login}" mode="K" mask="x0001" pass="{$model->password}"/>
XML;
        }

        $xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<courier dt="2018-04-13 01:00:05">
  <menu login="xFFFF" name="РЕЖИМЫ РАБОТЫ">
    <menu login="1" image="delivery.b16" name="РАБОТА С ЗНД" func="Seller_Start" />
    <menu login="1" image="kassa.b16" name="РАБОТА С ЧЕКАМИ" func="Seller_UserStart" />
    <menu login="1" image="addition.b16" name="ДОПОЛНИТЕЛЬНО">
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
      <menu image="clock.b16" name="УСТ.ДАТЫ/ВРЕМЕНИ" func="FR_SetDateTime" />
      <menu image="inspektor.b16" name="СЕРВИС ФИСК.РЕГ" func="FR_InspectorService" />
      <menu image="bank.b16" name="СЕРВИС БАНКА" func="CardModule_Service" />
      <menu image="connect.b16" name="КОММУНИКАЦИИ" func="Conn_SetParam" />
      <menu image="license.b16" name="ЛИЦЕНЗИЯ" func="Common_LicInfo" />
      <menu image="clean.b16" name="ОЧИСТКА ККМ" func="Seller_Clean" />
      <menu name="ОЧИСТКА ЭЛ.ЖУРНАЛА" func="EJ_Clear" />
    </menu>
    <menu image="info.b16" name="ИНФОРМАЦИЯ" func="Info_View" />
  </menu>

  <terminal>
    <par var="persist.sys.backlighttime" value="5" />
    <par var="persist.sys.sleeptime" value="5" />
    <par var="persist.sys.sleepwaiting" value="0" />
    <par var="persist.sys.sound.enable" value="1" />
    <par var="persist.sys.sound.volume" value="30" />
    <par var="persist.sys.key.backlight" value="0" />
    <par var="persist.sys.lcd.brightness" value="8" />
  </terminal>

  <auth link="auth.xml">
    <sys name="Системный администратор" hash="3A113FDADB478B0A4183C62DD856A511E6C37554" type="SHA1" mode="S" mask="xFFFF"/>
    <adm name="Администратор" login="0000" hash="92AAAB09CA2412A82A2FBF5CDC2DB340B9A3FA79" type="SHA1" mode="A" mask="x00FF"/>{$xml_logins}
  </auth>

  <shift>
    <params param1="x8064" param2="0" linefeed="0" />
    <settings modeopenshift="1" useshiftnumber="1" usegoodlist="1" addgoodmode="1" lowchargelevel="10" flagcardtotal="2" salecopies="0" bankcopies="1" taxsumenable="1" />
    <orderparams client="Розничный покупатель">
      <cancelreasons menuname="ПРИЧИНА ОТКАЗА" deficit="0">
        <reason type="0" name="НЕДОСДАЧА"/>
        <reason type="1" name="ОТКАЗ ПОКУПАТЕЛЯ"/>
        <reason type="2" name="НЕ ТОВАРНЫЙ ВИД"/>
        <reason type="3" name="НЕ ТОТ РАЗМЕР"/>
        <reason type="4" name="НЕ ТОТ ЦВЕТ"/>
        <reason type="5" name="ПРОЧЕЕ"/>
      </cancelreasons>

      <cancelorder menuname="ПРИЧИНА ОТМЕНЫ" zreport="00" >
        <reason type="01" name="ДОСТАВКА ОТЛОЖЕНА"/>
        <reason type="02" name="ОТКАЗ ОТ ДОСТАВКИ"/>
        <reason type="03" name="ЗНД НЕТ В МАШИНЕ"/>
      </cancelorder>
    </orderparams>

    <payments>
      <payment index="2" secondline="0" returnchange="0" currencyindex="0" maskofoper="x07" exchangecourse="1.00" name="КАРТА" />
    </payments>

    <taxesfn>
      <tax index="0" id="0" />
      <tax index="4" id="1" />
      <tax index="5" id="2" />
      <tax index="1" id="3" />
    </taxesfn>

    <totaldialog>
      <item fiscalindex="0" typeofpayment="1" name="НАЛИЧНЫЕ" />
      <item fiscalindex="2" typeofpayment="2" name="КАРТА" />
      <item fiscalindex="xFF" name="АННУЛИРОВАТЬ ЧЕК" />
    </totaldialog>

    <ofd name="MegaFon" mode="2" cardstop="1" period="300" sendtime="15" recvtime="60" />
  </shift>

  <common>
    <workdir>work</workdir>
    <frserialpattern>%S</frserialpattern>
    <timefmt>%F %T</timefmt>
  </common>

  <connections>
    <!--<conn name="WIFI" type="WIFI" apn="IRAS" pass="" dhcp="1" timeout="45" />-->
    <conn name="Megafon" type="GPRS" apn="internet.megafon.ru" login="megafon" pass="megafon" timeout="60" />
    <!--<conn name="TELE2" type="GPRS" apn="internet.tele2.ru" timeout="60" />-->
    <!--<conn name="MTS" type="GPRS" apn="internet.mts.ru" login="mts" pass="mts" timeout="60" />-->
    <conn name="USB-ETH" type="ETH1" dhcp="1" timeout="45" />
  </connections>

  <client>
    <loadorderlist url="https://api2.gemotest.ru/exchange/iras/loadorderlist/num{$kkm}" order_path="loadorder" goodlist_path="loadgoodlist" login="" pass="" ca="ca.p7b"/>
    <loadgoodlist url="https://api2.gemotest.ru/exchange/iras/loadgoodlist/num{$kkm}" login="" pass="" ca="ca.p7b"/>
    <loadorder url="https://api2.gemotest.ru/exchange/iras/loadorder/num{$kkm}" login="" pass="" ca="ca.p7b"/>
    <uploadorder url="https://api2.gemotest.ru/exchange/iras/uploadorder/num{$kkm}" order_path="check" shift_path="shift" log_path="log" login="" pass="" ca="ca.p7b"/>
    <uploadorderpacket url="https://api2.gemotest.ru/exchange/iras/uploadorderpacket/num{$kkm}" login="" pass="" ca="ca.p7b"/>
    <updateapp url="https://api2.gemotest.ru/exchange/iras/updateapp/num{$kkm}" login="" pass="" ca="ca.p7b"/>
    <updateconfig url="https://api2.gemotest.ru/exchange/iras/updateconfig/num{$kkm}" login="" pass="" ca="ca.p7b"/>
    <updateauth url="https://api2.gemotest.ru/exchange/iras/updateauth/num{$kkm}" login="" pass="" ca="ca.p7b"/>
    <updatelic url="https://api2.gemotest.ru/exchange/iras/updatelicense/num{$kkm}" ca="ca.p7b"/>
    <updatecert url="https://api2.gemotest.ru/exchange/iras/updatecertificate/num{$kkm}" login="" pass="" ca="ca.p7b"/>
    <updateos url="https://api2.gemotest.ru/exchange/iras/updateos/num{$kkm}" login="" pass="" ca="ca.p7b"/>
  </client>
</courier>
XML;
        header('Content-Type: text/plain; charset=utf-8');
        header('Content-Disposition: attachment; filename="settings.xml"');
        return $xml;
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
