<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "OrdersToExport".
 *
 * @property integer $AID
 * @property integer $OrderIDForCACHE
 * @property integer $ExtOrderId
 * @property integer $PatID
 * @property integer $Status
 * @property string $DateIns
 * @property string $LastUpdate
 * @property string $DateReg
 * @property string $TypeUserIns
 * @property string $KeyUserIns
 * @property string $TypeUserReg
 * @property string $KeyUserReg
 * @property string $TypeUserDel
 * @property string $KeyUserDel
 * @property string $DateDel
 * @property string $CacheOrderID
 * @property string $OrderCost
 * @property integer $OrderDiscountID
 * @property integer $OrderDiscount
 * @property string $OrderAllCost
 * @property integer $PercentOfComplite
 * @property integer $BeremDays
 * @property integer $UpdateCounts
 * @property string $OrderKontragentID
 * @property string $OrderDoctorID
 * @property string $OrderDoctorKID
 * @property string $OrderDoctorUZIID
 * @property string $OrderDoctorType
 * @property string $DayOfCycle
 * @property integer $UniversalOrderID
 * @property integer $IsExported
 * @property string $DateExport
 * @property string $InsUserIP
 * @property string $PayType
 * @property integer $BeforeDeleteStatus
 * @property integer $FromImport
 * @property integer $New
 * @property integer $garantletter
 * @property integer $cito
 * @property integer $checkPrintStatus
 * @property integer $version
 * @property string $Prdstavitel
 * @property integer $notifyQueued
 * @property integer $isEditing
 * @property integer $isComplite
 * @property string $CompliteDate
 * @property string $External
 * @property integer $req_passport
 * @property string $PatMedHistory
 * @property string $PatDepartment
 * @property string $DateResult
 * @property string $order_num
 * @property integer $percent_complete
 * @property integer $sms_status
 * @property string $file_guarantee_letter
 * @property integer $saved_status
 * @property integer $card_issued
 * @property integer $erp_order_id
 * @property string $bonuses
 * @property string $discount_card
 * @property string $bonus_balance
 * @property integer $is_print_blank
 * @property integer $disabled_discount
 * @property integer $auto_discount_id
 * @property integer $kd_status
 * @property integer $payment_type
 * @property string $parentOrderId
 * @property string $workshift_id
 * @property string $create_employee_guid
 * @property string $register_employee_guid
 * @property integer $sync_with_lc_status
 * @property integer $read_only
 * @property integer $create_user_id
 * @property integer $register_user_id
 * @property string $sync_with_lc_date
 */
class OrdersToExport extends \yii\db\ActiveRecord
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
    public static function tableName()
    {
        return 'OrdersToExport';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['OrderIDForCACHE', 'ExtOrderId', 'PatID', 'Status', 'OrderDiscountID', 'OrderDiscount', 'PercentOfComplite', 'BeremDays', 'UpdateCounts', 'UniversalOrderID', 'IsExported', 'BeforeDeleteStatus', 'FromImport', 'New', 'garantletter', 'cito', 'checkPrintStatus', 'version', 'notifyQueued', 'isEditing', 'isComplite', 'req_passport', 'percent_complete', 'sms_status', 'saved_status', 'card_issued', 'erp_order_id', 'is_print_blank', 'disabled_discount', 'auto_discount_id', 'kd_status', 'payment_type', 'sync_with_lc_status', 'read_only', 'create_user_id', 'register_user_id'], 'integer'],
            [['PatID'], 'required'],
            [['DateIns', 'LastUpdate', 'DateReg', 'DateDel', 'DateExport', 'CompliteDate', 'DateResult', 'sync_with_lc_date'], 'safe'],
            [['TypeUserIns', 'KeyUserIns', 'TypeUserReg', 'KeyUserReg', 'TypeUserDel', 'KeyUserDel', 'CacheOrderID', 'OrderKontragentID', 'OrderDoctorID', 'OrderDoctorKID', 'OrderDoctorUZIID', 'OrderDoctorType', 'DayOfCycle', 'InsUserIP', 'PayType', 'Prdstavitel', 'External', 'PatMedHistory', 'PatDepartment', 'order_num', 'file_guarantee_letter', 'discount_card', 'parentOrderId', 'workshift_id', 'create_employee_guid', 'register_employee_guid'], 'string'],
            [['OrderCost', 'OrderAllCost', 'bonuses', 'bonus_balance'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'AID' => 'Aid',
            'OrderIDForCACHE' => 'Order Idfor Cache',
            'ExtOrderId' => 'Ext Order ID',
            'PatID' => 'Pat ID',
            'Status' => 'Status',
            'DateIns' => 'Date Ins',
            'LastUpdate' => 'Last Update',
            'DateReg' => 'Date Reg',
            'TypeUserIns' => 'Type User Ins',
            'KeyUserIns' => 'Key User Ins',
            'TypeUserReg' => 'Type User Reg',
            'KeyUserReg' => 'Key User Reg',
            'TypeUserDel' => 'Type User Del',
            'KeyUserDel' => 'Key User Del',
            'DateDel' => 'Date Del',
            'CacheOrderID' => 'Cache Order ID',
            'OrderCost' => 'Order Cost',
            'OrderDiscountID' => 'Order Discount ID',
            'OrderDiscount' => 'Order Discount',
            'OrderAllCost' => 'Order All Cost',
            'PercentOfComplite' => 'Percent Of Complite',
            'BeremDays' => 'Berem Days',
            'UpdateCounts' => 'Update Counts',
            'OrderKontragentID' => 'Order Kontragent ID',
            'OrderDoctorID' => 'Order Doctor ID',
            'OrderDoctorKID' => 'Order Doctor Kid',
            'OrderDoctorUZIID' => 'Order Doctor Uziid',
            'OrderDoctorType' => 'Order Doctor Type',
            'DayOfCycle' => 'Day Of Cycle',
            'UniversalOrderID' => 'Universal Order ID',
            'IsExported' => 'Is Exported',
            'DateExport' => 'Date Export',
            'InsUserIP' => 'Ins User Ip',
            'PayType' => 'Pay Type',
            'BeforeDeleteStatus' => 'Before Delete Status',
            'FromImport' => 'From Import',
            'New' => 'New',
            'garantletter' => 'Garantletter',
            'cito' => 'Cito',
            'checkPrintStatus' => 'Check Print Status',
            'version' => 'Version',
            'Prdstavitel' => 'Prdstavitel',
            'notifyQueued' => 'Notify Queued',
            'isEditing' => 'Is Editing',
            'isComplite' => 'Is Complite',
            'CompliteDate' => 'Complite Date',
            'External' => 'External',
            'req_passport' => 'Req Passport',
            'PatMedHistory' => 'Pat Med History',
            'PatDepartment' => 'Pat Department',
            'DateResult' => 'Date Result',
            'order_num' => 'Order Num',
            'percent_complete' => 'Percent Complete',
            'sms_status' => 'Sms Status',
            'file_guarantee_letter' => 'File Guarantee Letter',
            'saved_status' => 'Saved Status',
            'card_issued' => 'Card Issued',
            'erp_order_id' => 'Erp Order ID',
            'bonuses' => 'Bonuses',
            'discount_card' => 'Discount Card',
            'bonus_balance' => 'Bonus Balance',
            'is_print_blank' => 'Is Print Blank',
            'disabled_discount' => 'Disabled Discount',
            'auto_discount_id' => 'Auto Discount ID',
            'kd_status' => 'Kd Status',
            'payment_type' => 'Payment Type',
            'parentOrderId' => 'Parent Order ID',
            'workshift_id' => 'Workshift ID',
            'create_employee_guid' => 'Create Employee Guid',
            'register_employee_guid' => 'Register Employee Guid',
            'sync_with_lc_status' => 'Sync With Lc Status',
            'read_only' => 'Read Only',
            'create_user_id' => 'Create User ID',
            'register_user_id' => 'Register User ID',
            'sync_with_lc_date' => 'Sync With Lc Date',
        ];
    }
}
