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
            [['DateIns', 'LastUpdate', 'DateReg', 'TypeUserIns', 'KeyUserIns', 'TypeUserReg', 'KeyUserReg', 'TypeUserDel', 'KeyUserDel', 'DateDel', 'CacheOrderID', 'OrderKontragentID', 'OrderDoctorID', 'OrderDoctorKID', 'OrderDoctorUZIID', 'OrderDoctorType', 'DayOfCycle', 'DateExport', 'InsUserIP', 'PayType', 'Prdstavitel', 'CompliteDate', 'External', 'PatMedHistory', 'PatDepartment', 'DateResult', 'order_num', 'file_guarantee_letter', 'discount_card', 'parentOrderId', 'workshift_id', 'create_employee_guid', 'register_employee_guid', 'sync_with_lc_date'], 'safe'],
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

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'AID' => $this->AID,
            'OrderIDForCACHE' => $this->OrderIDForCACHE,
            'ExtOrderId' => $this->ExtOrderId,
            'PatID' => $this->PatID,
            'Status' => $this->Status,
            'DateIns' => $this->DateIns,
            'LastUpdate' => $this->LastUpdate,
            'DateReg' => $this->DateReg,
            'DateDel' => $this->DateDel,
            'OrderCost' => $this->OrderCost,
            'OrderDiscountID' => $this->OrderDiscountID,
            'OrderDiscount' => $this->OrderDiscount,
            'OrderAllCost' => $this->OrderAllCost,
            'PercentOfComplite' => $this->PercentOfComplite,
            'BeremDays' => $this->BeremDays,
            'UpdateCounts' => $this->UpdateCounts,
            'UniversalOrderID' => $this->UniversalOrderID,
            'IsExported' => $this->IsExported,
            'DateExport' => $this->DateExport,
            'BeforeDeleteStatus' => $this->BeforeDeleteStatus,
            'FromImport' => $this->FromImport,
            'New' => $this->New,
            'garantletter' => $this->garantletter,
            'cito' => $this->cito,
            'checkPrintStatus' => $this->checkPrintStatus,
            'version' => $this->version,
            'notifyQueued' => $this->notifyQueued,
            'isEditing' => $this->isEditing,
            'isComplite' => $this->isComplite,
            'CompliteDate' => $this->CompliteDate,
            'req_passport' => $this->req_passport,
            'DateResult' => $this->DateResult,
            'percent_complete' => $this->percent_complete,
            'sms_status' => $this->sms_status,
            'saved_status' => $this->saved_status,
            'card_issued' => $this->card_issued,
            'erp_order_id' => $this->erp_order_id,
            'bonuses' => $this->bonuses,
            'bonus_balance' => $this->bonus_balance,
            'is_print_blank' => $this->is_print_blank,
            'disabled_discount' => $this->disabled_discount,
            'auto_discount_id' => $this->auto_discount_id,
            'kd_status' => $this->kd_status,
            'payment_type' => $this->payment_type,
            'sync_with_lc_status' => $this->sync_with_lc_status,
            'read_only' => $this->read_only,
            'create_user_id' => $this->create_user_id,
            'register_user_id' => $this->register_user_id,
            'sync_with_lc_date' => $this->sync_with_lc_date,
        ]);

        $query->andFilterWhere(['like', 'TypeUserIns', $this->TypeUserIns])
            ->andFilterWhere(['like', 'KeyUserIns', $this->KeyUserIns])
            ->andFilterWhere(['like', 'TypeUserReg', $this->TypeUserReg])
            ->andFilterWhere(['like', 'KeyUserReg', $this->KeyUserReg])
            ->andFilterWhere(['like', 'TypeUserDel', $this->TypeUserDel])
            ->andFilterWhere(['like', 'KeyUserDel', $this->KeyUserDel])
            ->andFilterWhere(['like', 'CacheOrderID', $this->CacheOrderID])
            ->andFilterWhere(['like', 'OrderKontragentID', $this->OrderKontragentID])
            ->andFilterWhere(['like', 'OrderDoctorID', $this->OrderDoctorID])
            ->andFilterWhere(['like', 'OrderDoctorKID', $this->OrderDoctorKID])
            ->andFilterWhere(['like', 'OrderDoctorUZIID', $this->OrderDoctorUZIID])
            ->andFilterWhere(['like', 'OrderDoctorType', $this->OrderDoctorType])
            ->andFilterWhere(['like', 'DayOfCycle', $this->DayOfCycle])
            ->andFilterWhere(['like', 'InsUserIP', $this->InsUserIP])
            ->andFilterWhere(['like', 'PayType', $this->PayType])
            ->andFilterWhere(['like', 'Prdstavitel', $this->Prdstavitel])
            ->andFilterWhere(['like', 'External', $this->External])
            ->andFilterWhere(['like', 'PatMedHistory', $this->PatMedHistory])
            ->andFilterWhere(['like', 'PatDepartment', $this->PatDepartment])
            ->andFilterWhere(['like', 'order_num', $this->order_num])
            ->andFilterWhere(['like', 'file_guarantee_letter', $this->file_guarantee_letter])
            ->andFilterWhere(['like', 'discount_card', $this->discount_card])
            ->andFilterWhere(['like', 'parentOrderId', $this->parentOrderId])
            ->andFilterWhere(['like', 'workshift_id', $this->workshift_id])
            ->andFilterWhere(['like', 'create_employee_guid', $this->create_employee_guid])
            ->andFilterWhere(['like', 'register_employee_guid', $this->register_employee_guid]);

        return $dataProvider;
    }
}
