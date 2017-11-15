<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dbo.med_ReturnOrderDetail".
 *
 * @property integer $id
 * @property integer $return_id
 * @property integer $order_id
 * @property string $service_id
 * @property string $total
 * @property string $price
 */
class MedReturnOrderDetail extends \yii\db\ActiveRecord
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
        return 'dbo.med_ReturnOrderDetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['return_id', 'order_id'], 'integer'],
            [['service_id'], 'string'],
            [['total', 'price'], 'number'],
            [['return_id'], 'exist', 'skipOnError' => true, 'targetClass' => MedReturnOrder::className(), 'targetAttribute' => ['return_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'return_id' => 'Return ID',
            'order_id' => 'Order ID',
            'service_id' => 'Service ID',
            'total' => 'Total',
            'price' => 'Price',
        ];
    }
}
