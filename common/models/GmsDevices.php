<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gms_devices".
 *
 * @property integer $id
 * @property string $sender_id
 * @property string $host_name
 * @property string $device_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $playlist
 */
class GmsDevices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gms_devices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_id', 'host_name', 'device_id', 'playlist'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['sender_id', 'host_name', 'device_id', 'playlist'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_id' => 'Sender ID',
            'host_name' => 'Host Name',
            'device_id' => 'Device ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'playlist' => 'Playlist',
        ];
    }
}
