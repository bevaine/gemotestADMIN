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
            [['date', 'patient_birthday'], 'safe'],
            [['order_num', 'base_doc_date', 'patient_fio', 'patient_phone', 'login_key', 'login_fio', 'sender_id', 'sender_name', 'discount_card', 'discount_name', 'app_version', 'kkm', 'z_num'], 'string'],
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
            'date' => 'Дата платежа',
            'order_num' => '№ заказа',
            'order_data' => 'Дата заказа',
            'base_doc_id' => 'Base Doc ID',
            'base_doc_type' => 'Base Doc Type',
            'base_doc_date' => 'Base Doc Date',
            'patient_id' => 'Пациент ID',
            'patient_fio' => 'Пациент ФИО',
            'patient_phone' => 'Телефон пациента',
            'patient_birthday' => 'День рожд. пациента',
            'login_id' => 'ID сотрудника',
            'login_key' => 'Код сотрудника',
            'login_type' => 'Тип сотрудника',
            'login_fio' => 'Сотрудник ФИО',
            'sender_id' => '№ отделения',
            'sender_name' => 'Название отделения',
            'pay_type' => 'Тип оплаты',
            'pay_type_original' => 'Тип оплаты ориг.',
            'cost' => 'Стоимость',
            'total' => 'Сумма',
            'kkm' => '№ ККМ',
            'z_num' => 'Z-отчет',
            'discount_card' => 'Дисконтная карта',
            'discount_id' => 'Discount ID',
            'discount_name' => 'Название скидки',
            'discount_percent' => 'Процент скидки',
            'bonus' => 'Бонус',
            'discount_total' => 'Сумма скидки',
            'cito_factor' => 'Cito Factor',
            'bonus_balance' => 'Бонусный баланс',
            'printlist' => 'Printlist',
            'free_pay' => 'Свободный чек',
            'app_version' => 'App Version',
        ];
    }

    /**
     * @param null $id
     * @return array|mixed
     */
    public static function getPayTypeArray($id = null) {
        $arr =  ['0' => 'Наличные', '1' => 'Кред. карта', '2' => 'Безналичный'];
        return is_null($id) ? $arr : $arr[$id];
    }
}
