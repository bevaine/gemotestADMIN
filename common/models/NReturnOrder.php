<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "n_ReturnOrder".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $parent_type
 * @property string $date
 * @property string $order_num
 * @property integer $status
 * @property string $total
 * @property integer $user_id
 * @property string $kkm
 * @property integer $sync_with_lc_status
 * @property string $last_update
 * @property string $sync_with_lc_date
 *
 * @property NReturnOrderDetail[] $nReturnOrderDetails
 */
class NReturnOrder extends \yii\db\ActiveRecord
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
        return 'n_ReturnOrder';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'parent_type', 'status', 'user_id', 'sync_with_lc_status'], 'integer'],
            [['date', 'last_update', 'sync_with_lc_date'], 'safe'],
            [['order_num', 'kkm'], 'string'],
            [['total'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'parent_type' => 'Parent Type',
            'date' => 'Date',
            'order_num' => 'Order Num',
            'status' => 'Status',
            'total' => 'Total',
            'user_id' => 'User ID',
            'kkm' => 'Kkm',
            'sync_with_lc_status' => 'Sync With Lc Status',
            'last_update' => 'Last Update',
            'sync_with_lc_date' => 'Sync With Lc Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNReturnOrderDetails()
    {
        return $this->hasMany(NReturnOrderDetail::className(), ['return_id' => 'id']);
    }
}
