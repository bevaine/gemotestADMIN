<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gms_video_history".
 *
 * @property integer $id
 * @property integer $pls_id
 * @property string $device_id
 * @property string $created_at
 * @property string $last_at
 * @property integer pls_pos
 * @property string pls_guid
 * @property integer $video_key
 * @property integer $type
 * @property integer $duration
 * @property GmsDevices $deviceModel
 * @property GmsRegions $regionModel
 * @property GmsSenders $senderModel
 * @property GmsPlaylistOut $playListOutModel
 * @property GmsVideos $videoModel
 */
class GmsVideoHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gms_video_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'last_at', 'pls_id', 'video_key', 'pls_pos', 'type', 'duration', 'device_id'], 'integer'],
            [['pls_guid'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pls_id' => 'Плейлист',
            'device_name' => 'Устройство',
            'device_id' => 'ID устройства',
            'created_at' => 'Начало',
            'last_at' => 'Окончание',
            'video_key' => 'Видео',
            'sender_name' => 'Отделение',
            'pls_name' => 'Плейлист',
            'region_id' => 'Регион',
            'date_at' => 'Период воспр.',
            'start_at' => 'Начало',
            'pls_pos' => 'Позиция в плейлисте',
            'pls_guid' => 'GUID сгенерированного плейлиста',
            'type' => 'Тип видео',
            'duration' => 'Продолжительность'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceModel()
    {
        return $this->hasOne(GmsDevices::className(), ['id' => 'device_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegionModel()
    {
        return $this->hasOne(GmsRegions::className(), ['id' => 'region_id'])->via('deviceModel');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSenderModel()
    {
        return $this->hasOne(GmsSenders::className(), ['id' => 'sender_id'])->via('deviceModel');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlayListOutModel()
    {
        return $this->hasOne(GmsPlaylistOut::className(), ['id' => 'pls_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideoModel()
    {
        return $this->hasOne(GmsVideos::className(), ['id' => 'video_key']);
    }
}
