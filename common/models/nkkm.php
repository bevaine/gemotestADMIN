<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "n_kkm".
 *
 * @property int $id
 * @property string $number
 * @property string $name
 * @property int $type
 * @property string $sender
 * @property string $sender_key
 * @property string $organization
 * @property int $active
 * @property string $code_1c
 * @property int $autonomous_terminal
 * @property int $branch_id
 * @property string $registration_number
 * @property string $inventory_number
 * @property string $reg_number
 *
 * @property Kontragents $branch
 */
class nkkm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'n_kkm';
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
            [['number', 'name', 'sender', 'sender_key', 'organization', 'code_1c', 'registration_number', 'inventory_number', 'reg_number'], 'string'],
            [['type', 'active', 'autonomous_terminal', 'branch_id'], 'integer'],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kontragents::className(), 'targetAttribute' => ['branch_id' => 'AID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'name' => 'Name',
            'type' => 'Type',
            'sender' => 'Sender',
            'sender_key' => 'Sender Key',
            'organization' => 'Organization',
            'active' => 'Active',
            'code_1c' => 'Code 1c',
            'autonomous_terminal' => 'Autonomous Terminal',
            'branch_id' => 'Branch ID',
            'registration_number' => 'Registration Number',
            'inventory_number' => 'Inventory Number',
            'reg_number' => 'Reg Number',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Kontragents::className(), ['AID' => 'branch_id']);
    }
}
