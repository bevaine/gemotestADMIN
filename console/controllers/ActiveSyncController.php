<?php

namespace console\controllers;

use common\components\helpers\ActiveSyncHelper;
use common\models\NAdUsers;
use yii\console\Controller;
use yii\db\Expression;
use common\models\T23;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;
use Yii;
use yii\log\Logger;

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
    public function __construct($id, $module, array $config = [])
    {
        $this->connection = new \yii\db\Connection([
            'dsn' => 'sqlsrv:Server=sw-sky-cl;Database=OrdersFromCACHE',
            'username' => 'importfromcache',
            'password' => 'import',
            'charset' => 'utf8',
        ]);

        $this->connection->open();

        parent::__construct($id, $module, $config);

    }

    public function actionUpdate ()
    {
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
        Yii::getLogger()->log([
            'objectUserAD->save()' => 'qweqweqwe'
        ], Logger::LEVEL_WARNING, 'binary');

        exit;


        $activeSyncHelper = new ActiveSyncHelper();

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
        print_r($ActiveSyncHelper->resetPasswordAD('test777.test777'));
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