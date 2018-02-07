<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "med_Pay".
 *
 * @property integer $id
 * @property string $date
 * @property integer $order_id
 * @property integer $patient_id
 * @property string $patient_fio
 * @property string $patient_phone
 * @property string $patient_birthday
 * @property integer $user_id
 * @property string $user_username
 * @property string $office_id
 * @property string $office_name
 * @property integer $pay_type
 * @property string $cost
 * @property string $discount_total
 * @property string $total
 * @property integer $printlist
 * @property string $user_fio
 * @property integer $free_pay
 * @property integer $base_doc_type
 * @property integer $base_doc_id
 * @property integer $is_virtual
 * @property string $kkm
 * @property string $z_num
 * @property integer $pay_type_original
 */
class MedPay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'med_Pay';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('MIS');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'patient_birthday'], 'safe'],
            [['order_id', 'patient_id', 'patient_fio', 'user_id', 'user_username', 'office_id', 'office_name', 'pay_type', 'cost', 'total'], 'required'],
            [['order_id', 'patient_id', 'user_id', 'pay_type', 'printlist', 'free_pay', 'base_doc_type', 'base_doc_id', 'is_virtual', 'pay_type_original'], 'integer'],
            [['patient_fio', 'patient_phone', 'user_username', 'office_id', 'office_name', 'user_fio', 'kkm', 'z_num'], 'string'],
            [['cost', 'discount_total', 'total'], 'number'],
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
            'order_id' => 'Номер заказа',
            'patient_id' => 'ID Пациента',
            'patient_fio' => 'ФИО Пациента',
            'patient_phone' => 'Телефон',
            'patient_birthday' => 'День рождения',
            'user_id' => 'ID Пользов.',
            'user_username' => 'Имя пользов.',
            'office_id' => '№ отделения',
            'office_name' => 'Название офиса',
            'pay_type' => 'Тип платежа',
            'cost' => 'Стоимость',
            'discount_total' => 'Скидка',
            'total' => 'Сумма',
            'printlist' => 'Printlist',
            'user_fio' => 'ФИО пользов.',
            'free_pay' => 'Своб. чек',
            'base_doc_type' => 'Base Doc Type',
            'base_doc_id' => 'Base Doc ID',
            'is_virtual' => 'Вирт. опл.',
            'kkm' => 'ККМ',
            'z_num' => 'Z-отчет',
            'pay_type_original' => 'Тип оплат. ориг.',
        ];
    }
}
