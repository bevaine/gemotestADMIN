<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hr_public_employee".
 *
 * @property string $employee_id
 * @property string $user_id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $gender
 * @property string $birthday
 * @property string $hiring_date
 * @property string $fired_date
 * @property string $position_id
 * @property string $fact_address_id
 * @property string $reg_address_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property string $email
 * @property string $extension
 * @property string $mobile
 * @property string $guid
 * @property string $personnel_number
 * @property string $type_contract
 * @property string $person_guid
 */
class HrPublicEmployee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hr_public_employee';
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
            [['employee_id', 'user_id', 'first_name', 'middle_name', 'last_name', 'gender', 'birthday', 'hiring_date', 'fired_date', 'position_id', 'fact_address_id', 'reg_address_id', 'created_at', 'updated_at', 'deleted_at', 'email', 'extension', 'mobile', 'guid', 'personnel_number', 'type_contract', 'person_guid'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'employee_id' => 'Employee ID',
            'user_id' => 'User ID',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            'gender' => 'Gender',
            'birthday' => 'Birthday',
            'hiring_date' => 'Hiring Date',
            'fired_date' => 'Fired Date',
            'position_id' => 'Position ID',
            'fact_address_id' => 'Fact Address ID',
            'reg_address_id' => 'Reg Address ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'email' => 'Email',
            'extension' => 'Extension',
            'mobile' => 'Mobile',
            'guid' => 'Guid',
            'personnel_number' => 'Personnel Number',
            'type_contract' => 'Type Contract',
            'person_guid' => 'Person Guid',
        ];
    }
}
