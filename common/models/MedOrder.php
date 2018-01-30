<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "med_Order".
 *
 * @property integer $id
 * @property string $date
 * @property integer $patient_id
 * @property integer $user_id
 * @property integer $office_id
 * @property integer $status
 * @property string $discount
 * @property string $discount_name
 * @property string $representative
 * @property string $workshift_id
 * @property integer $guarantee_letter
 * @property string $guarantee_letter_file_path
 * @property string $guarantee_letter_file_name
 * @property integer $erp_order_id
 * @property string $create_employee_guid
 * @property integer $create_user_id
 * @property integer $discount_type
 *
 * @property MedAppointment[] $medAppointments
 */
class MedOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'med_Order';
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
            [['date', 'patient_id', 'user_id', 'office_id', 'status'], 'required'],
            [['date'], 'safe'],
            [['patient_id', 'user_id', 'office_id', 'status', 'guarantee_letter', 'erp_order_id', 'create_user_id', 'discount_type'], 'integer'],
            [['discount'], 'number'],
            [['discount_name', 'representative', 'workshift_id', 'guarantee_letter_file_path', 'guarantee_letter_file_name', 'create_employee_guid'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'patient_id' => 'Patient ID',
            'user_id' => 'User ID',
            'office_id' => 'Office ID',
            'status' => 'Status',
            'discount' => 'Discount',
            'discount_name' => 'Discount Name',
            'representative' => 'Representative',
            'workshift_id' => 'Workshift ID',
            'guarantee_letter' => 'Guarantee Letter',
            'guarantee_letter_file_path' => 'Guarantee Letter File Path',
            'guarantee_letter_file_name' => 'Guarantee Letter File Name',
            'erp_order_id' => 'Erp Order ID',
            'create_employee_guid' => 'Create Employee Guid',
            'create_user_id' => 'Create User ID',
            'discount_type' => 'Discount Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedAppointments()
    {
        return $this->hasMany(MedAppointment::className(), ['order_id' => 'id']);
    }
}
