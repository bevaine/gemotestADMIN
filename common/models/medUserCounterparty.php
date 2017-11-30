<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dbo.med_UserCounterparty".
 *
 * @property integer $user_id
 * @property integer $counterparty_id
 */
class medUserCounterparty extends \yii\db\ActiveRecord
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
        return 'dbo.med_UserCounterparty';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'counterparty_id'], 'required'],
            [['user_id', 'counterparty_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'counterparty_id' => 'Counterparty ID',
        ];
    }
}
