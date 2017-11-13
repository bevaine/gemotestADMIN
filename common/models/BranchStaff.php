<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "branch_staff".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $guid
 * @property string $sender_key
 * @property integer $prototype
 * @property string $date
 * @property string $personnel_number
 */
class BranchStaff extends \yii\db\ActiveRecord
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
        return 'branch_staff';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'middle_name', 'last_name', 'guid', 'sender_key', 'personnel_number'], 'string'],
            [['guid', 'prototype'], 'required'],
            [['prototype'], 'integer'],
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
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            'guid' => 'Guid',
            'sender_key' => 'Sender Key',
            'prototype' => 'Prototype',
            'date' => 'Date',
            'personnel_number' => 'Personnel Number',
        ];
    }
}
