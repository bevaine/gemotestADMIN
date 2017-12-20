<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "n_CashBalanceInLOFlow".
 *
 * @property integer $id
 * @property integer $cashbalance_id
 * @property string $sender_key
 * @property string $total
 * @property string $date
 * @property string $operation
 * @property string $balance
 * @property integer $workshift_id
 * @property string $operation_id
 */
class NCashBalanceInLOFlow extends \yii\db\ActiveRecord
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
        return 'n_CashBalanceInLOFlow';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cashbalance_id', 'sender_key', 'total', 'date', 'operation'], 'required'],
            [['cashbalance_id', 'workshift_id'], 'integer'],
            [['sender_key', 'operation', 'operation_id'], 'string'],
            [['total', 'balance'], 'number'],
            [['date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cashbalance_id' => 'Код движения ДС',
            'sender_key' => 'Код отделения',
            'total' => 'Сумма',
            'date' => 'Дата',
            'operation' => 'Операция',
            'balance' => 'Баланс',
            'workshift_id' => 'Код смены',
            'operation_id' => 'Код операции',
        ];
    }
}
