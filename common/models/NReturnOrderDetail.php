<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "n_ReturnOrderDetail".
 *
 * @property integer $id
 * @property integer $return_id
 * @property string $serv_id
 * @property string $total
 * @property string $price
 * @property string $cito_factor
 *
 * @property NReturnOrder $return
 */
class NReturnOrderDetail extends \yii\db\ActiveRecord
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
        return 'n_ReturnOrderDetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['return_id'], 'integer'],
            [['serv_id'], 'string'],
            [['total', 'price', 'cito_factor'], 'number'],
            [['return_id'], 'exist', 'skipOnError' => true, 'targetClass' => NReturnOrder::className(), 'targetAttribute' => ['return_id' => 'id']],
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
            'serv_id' => 'Serv ID',
            'total' => 'Total',
            'price' => 'Price',
            'cito_factor' => 'Cito Factor',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReturn()
    {
        return $this->hasOne(NReturnOrder::className(), ['id' => 'return_id']);
    }
}
