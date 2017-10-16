<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrdersToExport;

/**
 * OrdersToExportSearch represents the model behind the search form about `common\models\OrdersToExport`.
 */
class OrdersToExportSearch extends OrdersToExport
{
    public $date_from;
    public $date_to;
    public $keys;

    /**
     * @return null|object
     */
    public static function getDb() {
        return Yii::$app->get('GemoTestDB');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AID', 'OrderIDForCACHE', 'ExtOrderId', 'PatID', 'Status', 'OrderDiscountID', 'OrderDiscount', 'PercentOfComplite', 'BeremDays', 'UpdateCounts', 'UniversalOrderID', 'IsExported', 'BeforeDeleteStatus', 'FromImport', 'New', 'garantletter', 'cito', 'checkPrintStatus', 'version', 'notifyQueued', 'isEditing', 'isComplite', 'req_passport', 'percent_complete', 'sms_status', 'saved_status', 'card_issued', 'erp_order_id', 'is_print_blank', 'disabled_discount', 'auto_discount_id', 'kd_status', 'payment_type', 'sync_with_lc_status', 'read_only', 'create_user_id', 'register_user_id'], 'integer'],
            [['date_to', 'date_from', 'keys', 'DateIns', 'LastUpdate', 'DateReg', 'TypeUserIns', 'KeyUserIns', 'TypeUserReg', 'KeyUserReg', 'TypeUserDel', 'KeyUserDel', 'DateDel', 'CacheOrderID', 'OrderKontragentID', 'OrderDoctorID', 'OrderDoctorKID', 'OrderDoctorUZIID', 'OrderDoctorType', 'DayOfCycle', 'DateExport', 'InsUserIP', 'PayType', 'Prdstavitel', 'CompliteDate', 'External', 'PatMedHistory', 'PatDepartment', 'DateResult', 'order_num', 'file_guarantee_letter', 'discount_card', 'parentOrderId', 'workshift_id', 'create_employee_guid', 'register_employee_guid', 'sync_with_lc_date'], 'safe'],
            [['OrderCost', 'OrderAllCost', 'bonuses', 'bonus_balance'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = OrdersToExport::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->joinWith('logins');

        $query->andFilterWhere(['order_num' => $this->order_num])
            ->andFilterWhere(['like', 'OrderKontragentID', $this->OrderKontragentID]);

        $query->andFilterWhere(["[Status]" => 2])
            ->andFilterWhere(["UserType" => 4]);

        if (!empty($this->date_from)) {
            $query->andFilterWhere(['>=', 'datereg', date("Y-m-d 00:00:00.000", strtotime($this->date_from))]);
        }

        if (!empty($this->date_to)) {
            $query->andFilterWhere(['<=', 'datereg', date("Y-m-d 23:59:59.000", strtotime($this->date_to))]);
        }

        if (!empty($this->keys)) {
            $query->andFilterWhere(['in', 'OrderDoctorID', explode(',', $this->keys)]);
        }

        $query->orderBy(['[Name]' => SORT_DESC])
            ->orderBy(['datereg' => SORT_DESC]);

        return $dataProvider;
    }
}