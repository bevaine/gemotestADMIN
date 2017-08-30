<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "erp_nurses".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $zone_id
 * @property string $nurse_email
 * @property string $nurse_phone
 * @property string $nurse_key
 */
class ErpNurses extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'erp_nurses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'zone_id'], 'integer'],
            [['nurse_email', 'nurse_phone', 'nurse_key'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'zone_id' => 'Zone ID',
            'nurse_email' => 'Nurse Email',
            'nurse_phone' => 'Nurse Phone',
            'nurse_key' => 'Nurse Key',
        ];
    }
}
