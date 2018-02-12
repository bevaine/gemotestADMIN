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
            'first_name' => 'Имя',
            'middle_name' => 'Отчество',
            'last_name' => 'Фамилия',
            'guid' => 'Guid',
            'sender_key' => 'Отделение',
            'prototype' => 'Prototype',
            'date' => 'Дата',
            'personnel_number' => 'Персон. номер',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($findModel = HrPublicEmployee::findOne([
            'guid' => $this->guid
        ])->toArray()) {
            $this->load(['BranchStaff' => $findModel]);
            return true;
        } else return false;
    }
}