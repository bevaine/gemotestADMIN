<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "med_Appointment".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $date
 * @property integer $patient_id
 * @property integer $doctor_id
 * @property integer $user_id
 * @property integer $office_id
 * @property integer $nurse_id
 * @property string $doctor_guid
 * @property string $nurse_guid
 *
 * @property MedOrder $order
 * @property MedAppointmentDetail[] $medAppointmentDetails
 */
class MedAppointment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'med_Appointment';
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
            [['order_id', 'date', 'patient_id', 'user_id', 'office_id'], 'required'],
            [['order_id', 'patient_id', 'doctor_id', 'user_id', 'office_id', 'nurse_id'], 'integer'],
            [['date'], 'safe'],
            [['doctor_guid', 'nurse_guid'], 'string'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => MedOrder::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'date' => 'Date',
            'patient_id' => 'Patient ID',
            'doctor_id' => 'Doctor ID',
            'user_id' => 'User ID',
            'office_id' => 'Office ID',
            'nurse_id' => 'Nurse ID',
            'doctor_guid' => 'Doctor Guid',
            'nurse_guid' => 'Nurse Guid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(MedOrder::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedAppointmentDetails()
    {
        return $this->hasMany(MedAppointmentDetail::className(), ['appointment_id' => 'id']);
    }
}
