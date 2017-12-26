<?php

namespace common\models;

use Yii;
use common\models\NCashBalanceInLOFlow;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use yii\db\Exception;

/**
 * This is the model class for table "n_Encashment".
 *
 * @property integer $id
 * @property string $sender_key
 * @property string $total
 * @property string $user_aid
 * @property string $receipt_number
 * @property string $receipt_file
 * @property string $date
 * @property string $code_1c
 * @property integer $status
 * @property NEncashmentDetail $detail
 * @property NEncashmentDetail $detailOfficeSumm
 * @property NCashBalanceInLOFlow $cashBalanceInLOFlow
 * @property NWorkshift $getWorkShift
 */

class NEncashment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'n_Encashment';
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
            [['sender_key', 'total', 'user_aid', 'receipt_number', 'date'], 'required'],
            [['sender_key', 'user_aid', 'receipt_number', 'receipt_file', 'code_1c'], 'string'],
            [['total'], 'number'],
            [['date'], 'safe'],
            [['status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_key' => 'Код отделения',
            'total' => 'Сумма',
            'user_aid' => 'Код пользователя',
            'receipt_number' => 'Номер инкассации',
            'receipt_file' => 'Файл инкассации',
            'date' => 'Дата',
            'code_1c' => 'Код 1С',
            'status' => 'Статус',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetail()
    {
        return $this->hasMany(NEncashmentDetail::className(), ['encashment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetailOfficeSumm()
    {
        return $this->hasOne(NEncashmentDetail::className(), ['encashment_id' => 'id'])
            ->where(['target' => 'office_summ']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCashBalanceInLOFlow()
    {
        return $this->hasOne(NCashBalanceInLOFlow::className(), ['workshift_id' => 'id'])
            ->andWhere("n_CashBalanceInLOFlow.operation_id=:operation_id", [':operation_id' => 'encashment'])
            ->via('workShift');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkShift() {
        return $this->hasOne(NWorkshift::className(), [
            'sender_key' => 'sender_key',
            'user_aid' => 'user_aid'
        ])->where("CONVERT(DATETIME, FLOOR(CONVERT(float, n_workshift.[open_date]))) = :date", [
            ':date' => date('Y-m-d 00:00:00', strtotime($this->date))
        ]);
    }

}