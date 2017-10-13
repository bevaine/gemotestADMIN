<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "n_Pay".
 *
 * @property integer $id
 * @property string $date
 * @property string $order_num
 * @property string $order_data
 * @property integer $base_doc_id
 * @property integer $base_doc_type
 * @property string $base_doc_date
 * @property integer $patient_id
 * @property string $patient_fio
 * @property string $patient_phone
 * @property string $patient_birthday
 * @property integer $login_id
 * @property string $login_key
 * @property integer $login_type
 * @property string $login_fio
 * @property string $sender_id
 * @property string $sender_name
 * @property integer $pay_type
 * @property string $cost
 * @property string $discount_card
 * @property integer $discount_id
 * @property string $discount_name
 * @property string $discount_percent
 * @property string $bonus
 * @property string $discount_total
 * @property string $total
 * @property integer $cito_factor
 * @property string $bonus_balance
 * @property integer $printlist
 * @property integer $free_pay
 * @property string $app_version
 * @property string $kkm
 * @property string $z_num
 * @property integer $pay_type_original
 */
class NPay extends \yii\db\ActiveRecord
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
        return 'n_Pay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'order_data', 'base_doc_date', 'patient_birthday'], 'safe'],
            [['order_num', 'patient_fio', 'patient_phone', 'login_key', 'login_fio', 'sender_id', 'sender_name', 'discount_card', 'discount_name', 'app_version', 'kkm', 'z_num'], 'string'],
            [['order_data', 'base_doc_id', 'base_doc_type', 'base_doc_date', 'patient_id', 'patient_fio', 'patient_phone', 'login_key', 'login_fio', 'sender_id', 'sender_name', 'pay_type', 'cost', 'total'], 'required'],
            [['base_doc_id', 'base_doc_type', 'patient_id', 'login_id', 'login_type', 'pay_type', 'discount_id', 'cito_factor', 'printlist', 'free_pay', 'pay_type_original'], 'integer'],
            [['cost', 'discount_percent', 'bonus', 'discount_total', 'total', 'bonus_balance'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'order_num' => 'Order Num',
            'order_data' => 'Order Data',
            'base_doc_id' => 'Base Doc ID',
            'base_doc_type' => 'Base Doc Type',
            'base_doc_date' => 'Base Doc Date',
            'patient_id' => 'Patient ID',
            'patient_fio' => 'Patient Fio',
            'patient_phone' => 'Patient Phone',
            'patient_birthday' => 'Patient Birthday',
            'login_id' => 'Login ID',
            'login_key' => 'Login Key',
            'login_type' => 'Login Type',
            'login_fio' => 'Login Fio',
            'sender_id' => 'Sender ID',
            'sender_name' => 'Sender Name',
            'pay_type' => 'Pay Type',
            'cost' => 'Cost',
            'discount_card' => 'Discount Card',
            'discount_id' => 'Discount ID',
            'discount_name' => 'Discount Name',
            'discount_percent' => 'Discount Percent',
            'bonus' => 'Bonus',
            'discount_total' => 'Discount Total',
            'total' => 'Total',
            'cito_factor' => 'Cito Factor',
            'bonus_balance' => 'Bonus Balance',
            'printlist' => 'Printlist',
            'free_pay' => 'Free Pay',
            'app_version' => 'App Version',
            'kkm' => 'Kkm',
            'z_num' => 'Z Num',
            'pay_type_original' => 'Pay Type Original',
        ];
    }

}
