<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "med_CounterpartyPos".
 *
 * @property string $pos_key
 * @property integer $counterparty_id
 */
class MedCounterpartyPos extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'med_CounterpartyPos';
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
            [['pos_key', 'counterparty_id'], 'required'],
            [['pos_key'], 'string'],
            [['counterparty_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pos_key' => 'Pos Key',
            'counterparty_id' => 'Counterparty ID',
        ];
    }
}
