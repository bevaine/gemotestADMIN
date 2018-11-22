<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sms".
 *
 * @property string $id
 * @property string $status
 * @property string $client_id
 * @property string $phone
 * @property string $message
 * @property string $tz
 * @property int $priority
 * @property bool $enqueued
 * @property int $attempt
 * @property int $provider_id
 * @property string $provider_sms_id
 * @property string $deliver_sm
 * @property int $bounce_reason
 * @property string $created_at
 * @property string $updated_at
 * @property string $callback
 * @property int $attempts_get_status
 * @property OrdersToExport $order;
 * @property int $orderNum;
 * @property string $CompliteDate;
 */

class Sms extends \yii\db\ActiveRecord
{
    public $orderNum;
    public $CompliteDate;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sms';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('TentacleDb');
    }

    /**
     *
     */
    public function afterFind()
    {
        $expData = explode(' ', $this->message);
        if (OrdersToExport::findOne(['order_num' => $expData[3]])) {
            $this->orderNum = $expData[3];
        }
        parent::afterFind(); // TODO: Change the autogenerated stub
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'deliver_sm'], 'string'],
            [['phone'], 'required'],
            [['priority', 'attempt', 'provider_id', 'bounce_reason', 'attempts_get_status'], 'default', 'value' => null],
            [['priority', 'attempt', 'provider_id', 'bounce_reason', 'attempts_get_status'], 'integer'],
            [['enqueued'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['client_id', 'tz'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 20],
            [['message'], 'string', 'max' => 1024],
            [['provider_sms_id'], 'string', 'max' => 32],
            [['callback'], 'string', 'max' => 2048],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Статус',
            'client_id' => 'Client ID',
            'phone' => 'Номер',
            'message' => 'Сообщение',
            'tz' => 'Tz',
            'priority' => 'Приоритет',
            'enqueued' => 'Enqueued',
            'attempt' => 'Attempt',
            'provider_id' => 'Provider ID',
            'provider_sms_id' => 'Provider Sms ID',
            'deliver_sm' => 'Deliver Sm',
            'bounce_reason' => 'Bounce Reason',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата доставки',
            'callback' => 'Callback',
            'attempts_get_status' => 'Attempts Get Status',
            'orderNum' => '№ заказа',
            'CompliteDate' => 'Дата вып. исследов.'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(OrdersToExport::class, ['order_num' => 'orderNum']);
    }

    /**
     * @param null $id
     * @return array|mixed
     */
    public static function getStatusArray($id = null) {
        $arr = [
            'delivered' => 'Доставлено',
            'bounced' => 'Отказ',
            'pending' => 'В ожидании',
            'sent' => 'Отправлено',
            'buried' => 'Не доставлено'
        ];
        return is_null($id) ? $arr : $arr[$id];
    }
}