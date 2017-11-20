<?php

namespace console\controllers;

use common\components\helpers\ActiveSyncHelper;
use common\models\NAdUsers;
use yii\console\Controller;
use yii\db\Expression;
use common\models\T23;
use yii\helpers\ArrayHelper;
use Yii;

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