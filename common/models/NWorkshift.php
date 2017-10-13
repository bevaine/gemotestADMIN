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
            'user_aid' => 'User Aid',
            'sender_key' => 'Sender Key',
            'kkm' => 'Kkm',
            'z_num' => 'Z Num',
            'open_date' => 'Open Date',
            'close_date' => 'Close Date',
            'not_zero_sum_start' => 'Not Zero Sum Start',
            'not_zero_sum_end' => 'Not Zero Sum End',
            'amount_cash_register' => 'Amount Cash Register',
            'sender_key_close' => 'Sender Key Close',
            'error_check_count' => 'Error Check Count',
            'error_check_total_cash' => 'Error Check Total Cash',
            'error_check_total_card' => 'Error Check Total Card',
            'error_check_return_count' => 'Error Check Return Count',
            'error_check_return_total_cash' => 'Error Check Return Total Cash',
            'error_check_return_total_card' => 'Error Check Return Total Card',
            'file_name' => 'File Name',
            'code_1c' => 'Code 1c',
        ];
    }
}
