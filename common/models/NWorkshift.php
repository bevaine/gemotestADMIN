<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "n_workshift".
 *
 * @property integer $id
 * @property integer $user_aid
 * @property string $sender_key
 * @property string $kkm
 * @property string $z_num
 * @property string $open_date
 * @property string $close_date
 * @property string $not_zero_sum_start
 * @property string $not_zero_sum_end
 * @property string $amount_cash_register
 * @property string $sender_key_close
 * @property integer $error_check_count
 * @property string $error_check_total_cash
 * @property string $error_check_total_card
 * @property integer $error_check_return_count
 * @property string $error_check_return_total_cash
 * @property string $error_check_return_total_card
 * @property string $file_name
 * @property string $code_1c
 * @property NPay $pays
 * @property NPay $returnPays
 */
class NWorkshift extends \yii\db\ActiveRecord
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
        return 'n_workshift';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_aid', 'kkm', 'z_num', 'open_date'], 'required'],
            [['user_aid', 'error_check_count', 'error_check_return_count'], 'integer'],
            [['sender_key', 'kkm', 'z_num', 'sender_key_close', 'file_name', 'code_1c'], 'string'],
            [['open_date', 'close_date'], 'safe'],
            [['not_zero_sum_start', 'not_zero_sum_end', 'amount_cash_register', 'error_check_total_cash', 'error_check_total_card', 'error_check_return_total_cash', 'error_check_return_total_card'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_aid' => 'Aid',
            'sender_key' => 'Отд. открытия',
            'sender_key_close' => 'Отд. закрытия',
            'kkm' => 'ККМ',
            'z_num' => 'Z-отчет',
            'open_date' => 'Дата откр.',
            'close_date' => 'Дата закрыт.',
            'not_zero_sum_start' => 'Необнул. сумма на нач.',
            'not_zero_sum_end' => 'Необнул. сумма на кон.',
            'amount_cash_register' => 'Сумма нал.',
            'error_check_count' => 'Кол-во ошиб. чеков',
            'error_check_total_cash' => 'Сумма ошиб. нал.',
            'error_check_total_card' => 'Сумма ошиб. карта.',
            'error_check_return_count' => 'Кол-во чеков возвр.',
            'error_check_return_total_cash' => 'Сумма возврат. нал.',
            'error_check_return_total_card' => 'Сумма возврат. карта',
            'file_name' => 'Имя файла',
            'code_1c' => 'Код в 1С',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPays()
    {
        return $this->hasMany(NPay::className(), ['z_num' => 'z_num', 'kkm' => 'kkm'])
            ->where(['base_doc_type' => 1]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReturnPays()
    {
        return $this->hasMany(NPay::className(), ['z_num' => 'z_num', 'kkm' => 'kkm'])
            ->where(['base_doc_type' => 2]);
    }
}
