<?php

namespace console\controllers;

use common\components\helpers\ActiveSyncHelper;
use common\models\GmsPlaylistOut;
use common\models\Logins;
use common\models\NAdUsers;
use common\models\Permissions;
use yii\console\Controller;
use yii\db\Expression;
use common\models\T23;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;
use Yii;
use yii\log\Logger;
use common\models\NAuthASsignment;
use yii\base\Exception;
use common\models\BranchStaff;

/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 22.08.2017
 * Time: 14:36
 */
class ActiveSyncController extends Controller
{

    public $connection;

    /**
     * ActiveSyncController constructor.
     * @param string $id
     * @param \yii\base\Module $module
     * @param array $config
     */
//    public function __construct($id, $module, array $config = [])
//    {
//        $this->connection = new \yii\db\Connection([
//            'dsn' => 'sqlsrv:Server=sw-sky-cl;Database=OrdersFromCACHE',
//            'username' => 'importfromcache',
//            'password' => 'import',
//            'charset' => 'utf8',
//        ]);
//
//        $this->connection->open();
//
//        parent::__construct($id, $module, $config);
//
//    }

    public function actionUpdate ()
    {
        $findModel = Logins::find()
            ->where([
                    'OR',
                    ['like', '[Name]', 'менеджер'],
                    ['like', '[Name]', 'регистрат']
                ])
            ->andWhere(['is', 'date_end', null])
            ->all();

        $rowInsert = [];
        foreach ($findModel as $user) {
            $rowInsert[] = [
                'contingent_donor',
                $user->aid,
                'N;'
            ];
        }

        $connection = 'GemoTestDB';
        $db = Yii::$app->$connection;

        $db->createCommand()->batchInsert(
            NAuthASsignment::tableName(),
            ['itemname', 'userid', 'data'],
            $rowInsert
        )->execute();

        exit;

        $i = 0;

        $models = NAdUsers::find()
            ->andWhere(['is not', 'gs_id', null])
            ->andWhere(['=' , 'gs_usertype', 7])
            ->all();

        foreach ($models as $model) {
            /* @var \common\models\NAdUsers $model */

            $i = $i + 1;
            $GUID = NULL;
            $tableNumber = NULL;

            if (!empty($model->last_name)
                && !empty($model->first_name)
                && !empty($model->middle_name)) {

                $model23 = T23::find()->where([
                    'q5' => $model->last_name,
                    'q3' => $model->first_name,
                    'q4' => $model->middle_name,
                ]);

                if ($model23) {
                    if ($model23->count() == 1) {
                        /* @var \common\models\T23 $findT23 */
                        $findT23 = $model23->one();
                        if (!empty($findT23->q19) || !empty($findT23->q20)) {
                            if (!empty($findT23->q19)) {
                                $GUID = $findT23->q19;
                            }
                            if (!empty($findT23->q20)) {
                                $tableNumber = $findT23->q20;
                            }
                        }
                    } elseif ($model23->count() > 1) {

                        $arrModels = $model23->asArray()->all();
                        $d1 = ArrayHelper::index($arrModels, 'q1');
                        $d2 = ArrayHelper::getColumn($arrModels, 'q1');
                        $d3 = $d1[max($d2)];

                        if (!empty($d3["q19"]) || !empty($d3["q20"])) {
                            if (!empty($d3["q19"])) {
                                $GUID = $d3["q19"];
                            }
                            if (!empty($d3["q20"])) {
                                $tableNumber = $d3["q20"];
                            }
                        }
                    }
                }
            }

            $sql = 'UPDATE 
                        n_ad_Users 
                    SET 
                        subdivision = :GUID, table_number = :tableNumber 
                    WHERE 
                        last_name=:last_name AND first_name=:first_name AND middle_name=:middle_name';

            $this->connection->createCommand($sql, [
                ':GUID' => trim($GUID),
                ':tableNumber' => trim($tableNumber),
                ':last_name' => $model->last_name,
                ':first_name' => $model->first_name,
                ':middle_name' => $model->middle_name,
            ])->execute();

            echo "\r\n" . $i . '.Обновлены данные по сотруднику - ' . $model->last_name . ' ' .$model->first_name .' '. $model->middle_name . ' '. $GUID;

            $this->connection->close();

        }
    }

    public function actionTest ()
    {
        $d = GmsPlaylistOut::findOne(12);
        $d->getVideos();


    // $weekKeys = array_combine(array_keys(array_fill(1, 7, '')), array_keys(GmsPlaylistOut::WEEK));
        //$d = strtotime("23-12-2017");
        print_r($a);
        //echo date("Y-m-d H:i:s", 1520283600)."\r\n";
//        echo date("Y-m-d H:i:s", 1520283600)."\r\n";
//        echo date("Y-m-d H:i:s", 6060)."\r\n";
//        echo date("Y-m-d H:i:s", 42060)."\r\n";

        //$tomorrow  = mktime(0, 0, 0, date("m", $startDate)  , date("d", $startDate)+1, date("Y" , $startDate));
        exit;
        print_r(\common\models\BranchStaffPrototype::getPrototypeList());
        exit;
        print_r(number_format(1554, 2, '.', ''));
        print_r(base64_decode('d9b1d7db4cd6e70935368a1efb10e377'));
        $d = BranchStaff::find()
            ->filterWhere(['like', 'last_name', null])
            ->andFilterWhere(['like', 'first_name', 'Иван'])
            ->andFilterWhere(['like', 'middle_name', 'qwe'])
            ->one();

        print_r($d);
        exit;



        echo str_replace('&quot;', '"', ' ООО &quot;Гемотест-Севастополь&quot; (Севастополь 2)');

        print_r(Logins::find()->where(['tbl' => 'DirectorFlo'])->max('[key]'));
        exit;
        $port = 22;
        $server = '192.168.156.2';
        $userLogin = 'itr';
        $userPassword = 'Gthtgenmt117!';

        $login = '5171-gd-515';
        $password = 'As1234567';
        $script = "sudo ./changePasswordSkynet.sh '".$login."' '".$password."'";

        $connection = ssh2_connect($server, $port);
        if ($connect = ssh2_auth_password($connection, $userLogin, $userPassword)) {
            ssh2_shell($connection, 'xterm');
            if (!ssh2_exec($connection, $script)) {
                return false;
            }
        }
        exit;
        //todo присвоение прав пользователю
        $findPermissions = Permissions::findAll([
            'department' => 0
        ]);

        if ($findPermissions) {
            $rowInsert = [];
            foreach ($findPermissions as $permission) {
                $rowInsert[] = [$permission->permission, '23423423', 'N;'];
            }
            try {
                $connection = 'GemoTestDB';
                $db = Yii::$app->$connection;
                $db->createCommand()->batchInsert(
                    NAuthASsignment::tableName(),
                    ['itemname', 'userid', 'data'],
                    $rowInsert
                )->execute();
            } catch (Exception $e) {
                Yii::getLogger()->log([
                    'addPermissions->batchInsert'=>$e->getMessage()
                ], Logger::LEVEL_ERROR, 'binary');
                return false;
            }
        }
        exit;

        $permissions = [
            '7' => [], //todo без прав
            '0' => ['mis','workshift.allow','MisManager','Operator','Registrar','Report.Workshift.Kkm','SkynetEstimationOrder'],//Cобственные отделения'
            '10' => ['mis','workshift.allow','MisManager','Operator','Registrar','Report.Workshift.Kkm','SkynetEstimationOrder'],//Cобственные отделения'
            '1' => ['admin','Administrator.Callcenter.index','mis','MisManager','Operator','Registrar','SkynetEstimationOrder'],//Контакт центр
            '2' => ['Operator','Registrar'],//Продажи
            '21' => ['Operator','Registrar'],//Продажи
            '22' => ['Operator','Registrar'],//Продажи
            '3' => ['admin','ClientManager','db_gemotest','directorFlo','franchisees_account','LisAdmin','mis','MisManager','Operator','Report.*','ReportOrders.*','ReportPrices.*'],//Развитие
            '31' => ['admin','ClientManager','db_gemotest','directorFlo','franchisees_account','LisAdmin','mis','MisManager','Operator','Report.*','ReportOrders.*','ReportPrices.*'],//Развитие
            '32' => ['admin','ClientManager','db_gemotest','directorFlo','franchisees_account','LisAdmin','mis','MisManager','Operator','Report.*','ReportOrders.*','ReportPrices.*'],//Развитие
            '33' => ['admin','ClientManager','db_gemotest','directorFlo','franchisees_account','LisAdmin','mis','MisManager','Operator','Report.*','ReportOrders.*','ReportPrices.*'],//Развитие
            '4' => ['admin','ClientManager','finance_manager','management_all_offices','Operator','Registrar','Report.Inoe','ReportOrders.Contingents','SkynetEstimationOrder'],//Отдел клиентской инф. поддержки
            '5' => ['Operator','ClientManager','MedRegistrar','Report.Inoe','PreanalyticaManager'],//Мед регистратор
            '6' => ['admin','Administrator.Callcenter.index','bonuses_view','cancelBm_view','ClientManager','discount_all_rights','kurs_view','mis','MisManager','Operator','Registrar','Report.MsZabor','Report.PollPatients','Report.Rep41','ReportOrders.Detail','ReportOrders.SummaryMonth','ReportPrices.Archive','ReportPrices.ByDate','ReportPrices.Detail','ReportPrices.History','SkynetEstimationOrder'],//Клиент-менеджер
            '8' => ['admin','Administrator.Callcenter.index','mis','MisManager','Operator','Registrar','SkynetEstimationOrder'],//todo доктор-консультант

        ];

        foreach ($permissions as $key => $department) {
            $rowInsert = [];
            foreach ($department as $permission) {
                $rowInsert[] = [$key, $permission];
            }
            try {
                $connection = 'db';
                $db = Yii::$app->$connection;
                $db->createCommand()->batchInsert(
                    Permissions::tableName(),
                    ['department', 'permission'],
                    $rowInsert
                )->execute();
            } catch (Exception $e) {
                Yii::getLogger()->log([
                    'addPermissions->batchInsert'=>$e->getMessage()
                ], Logger::LEVEL_ERROR, 'binary');
                return false;
            }
        }
        exit;



//        $ldapconn = ldap_connect('192.168.108.3');
//        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
//        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
//
//        if (!$ldapconn) return false;
//
//        $ldapbind = ldap_bind($ldapconn, self::LDAP_LOGIN, self::LDAP_PASSW);
//
        $model = Logins::findOne(['Key' => '2031']);
        if ($model->franchazy) {
            print_r($model->franchazy->BlankName);
        }
        exit;

        $activeSyncHelper = new ActiveSyncHelper();
        $activeSyncHelper->accountName = 'Дымченко Евгений Викторович';
        $activeSyncHelper->checkUserAccountAd();
        exit;


        Yii::getLogger()->log([
            'objectUserAD->save()' => 'qweqweqwe'
        ], Logger::LEVEL_WARNING, 'binary');

        exit;


        /**
         * @var $transaction Transaction
         */
        $transaction = NAdUsers::getDb()->beginTransaction();
        try {
            $objectUserAD = new NAdUsers();
            $objectUserAD->last_name = "WWW123";
            $objectUserAD->first_name = "WWW123";
            $objectUserAD->middle_name = "WWW123";
            $objectUserAD->AD_name = "WWW123";
            $objectUserAD->AD_position = "WWW123";
            $objectUserAD->AD_email = "WWW123";
            $objectUserAD->gs_email = "WWW123";
            $objectUserAD->gs_id = 123;
            $objectUserAD->gs_key = 123;
            $objectUserAD->gs_usertype = 123;
            $objectUserAD->AD_login = "WWW123";
            $objectUserAD->allow_gs = 1;
            $objectUserAD->active = 1;
            $objectUserAD->AD_active = 1;
            $objectUserAD->auth_ldap_only = 1;
            $objectUserAD->save();
            // ...другие операции с базой данных...
            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch(\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        print_r($transaction->db->getLastInsertID());

        exit;
        $customer = Customer::findOne(123);

        NAdUsers::getDb()->transaction(function($db) use ($customer) {
            $customer->id = 200;
            $customer->save();
            // ...другие операции с базой данных...
        });

// или по-другому
        $transaction = NAdUsers::getDb()->beginTransaction();
        try {
            $customer->id = 200;
            $customer->save();
            // ...другие операции с базой данных...
            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch(\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        $objectUserAD = new NAdUsers();
        $objectUserAD->last_name = "WWW123";
        $objectUserAD->first_name = "WWW123";
        $objectUserAD->middle_name = "WWW123";
        $objectUserAD->AD_name = "WWW123";
        $objectUserAD->AD_position = "WWW123";
        $objectUserAD->AD_email = "WWW123";
        $objectUserAD->gs_email = "WWW123";
        $objectUserAD->gs_id = 123;
        $objectUserAD->gs_key = 123;
        $objectUserAD->gs_usertype = 123;
        $objectUserAD->AD_login = "WWW123";
        $objectUserAD->allow_gs = 1;
        $objectUserAD->active = 1;
        $objectUserAD->AD_active = 1;
        $objectUserAD->auth_ldap_only = 1;

        $objectUserAD->save();
        echo $objectUserAD->ID;
        exit;





        $activeSyncHelper->fullName = 'Дымченко Евгений Викторович';

        //todo проверяем существует ли пользователь с ФИО в AD
        $arrAccountAD = $activeSyncHelper->checkUserNameAd();

        print_r($arrAccountAD);

        if (is_array($arrAccountAD) && count($arrAccountAD) > 1) {
            $arrAccounts = ArrayHelper::getColumn($arrAccountAD, 'account');
            print_r($arrAccounts);
        }

        //print_r($arrAccountAD);
        exit;

        print_r(\common\models\Doctors::getDoctorsList());
        exit;

        echo strpos('ldap_add(): Add: Constraint violation', 'Add: Constraint violation') !== false;
        exit;


        echo $this->translit($this->firstName.".".
            substr($this->middleName,0,1).".".$this->lastName);
        $ActiveSyncHelper = new ActiveSyncHelper();
        print_r(ActiveSyncHelper::resetPasswordAD('test777.test777'));
        exit;

        $ActiveSyncHelper->fullName = 'sdfsd dsfs dsdf';
        $ActiveSyncHelper->accountName = 'test7';
        $ActiveSyncHelper->typeLO = "SLO";
        $ActiveSyncHelper->firstName = "test7";
        $ActiveSyncHelper->lastName = "test7";
        $ActiveSyncHelper->operatorofficestatus = "sdfsd";
        print_r($ActiveSyncHelper->addNewUserAd());
        exit;
        $ActiveSyncHelper->nurse = 1;
        $ActiveSyncHelper->department = 0;
        //$ActiveSyncHelper->typeLO = "SLO";
        $ActiveSyncHelper->lastName = "Тюленев";
        $ActiveSyncHelper->firstName = "Дмитрий";
        $ActiveSyncHelper->middleName = "Андреевич";
        $ActiveSyncHelper->operatorofficestatus = "Оператор";
        $ActiveSyncHelper->fullName = $ActiveSyncHelper->lastName." ".$ActiveSyncHelper->firstName." ".$ActiveSyncHelper->middleName;
        print_r($ActiveSyncHelper->checkUserNameAd());
    }
}