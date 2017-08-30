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
            'cashbalance_id' => 'Cashbalance ID',
            'sender_key' => 'Sender Key',
            'total' => 'Total',
            'date' => 'Date',
            'operation' => 'Operation',
            'balance' => 'Balance',
            'workshift_id' => 'Workshift ID',
            'operation_id' => 'Operation ID',
        ];
    }
}
