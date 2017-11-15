<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dbo.med_ReturnOrder".
 *
 * @property integer $id
 * @property string $date
 * @property integer $order_id
 * @property integer $status
 * @property string $total
 * @property integer $user_id
 * @property integer $is_virtual
 * @property string $kkm
 * @property string $z_num
 * @property integer $pay_type
 * @property integer $pay_type_original
 * @property integer $is_freepay
 */
class MedReturnOrder extends \yii\db\ActiveRecord
{
    /**
     * @return null|object
     */
    public static function getDb() {
        return Yii::$app->get('MIS');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dbo.med_ReturnOrder';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['order_id', 'status', 'user_id', 'is_virtual', 'pay_type', 'pay_type_original', 'is_freepay'], 'integer'],
            [['total'], 'number'],
            [['kkm', 'z_num'], 'string'],
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
            'order_id' => 'Order ID',
            'status' => 'Status',
            'total' => 'Total',
            'user_id' => 'User ID',
            'is_virtual' => 'Is Virtual',
            'kkm' => 'Kkm',
            'z_num' => 'Z Num',
            'pay_type' => 'Pay Type',
            'pay_type_original' => 'Pay Type Original',
            'is_freepay' => 'Is Freepay',
        ];
    }
}
