<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "n_EncashmentDetail".
 *
 * @property integer $id
 * @property integer $encashment_id
 * @property string $target
 * @property string $total
 */
class NEncashmentDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'n_EncashmentDetail';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('GemoTestDB');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['encashment_id', 'target', 'total'], 'required'],
            [['encashment_id'], 'integer'],
            [['target'], 'string'],
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
            'encashment_id' => 'Encashment ID',
            'target' => 'Target',
            'total' => 'Total',
        ];
    }
}
