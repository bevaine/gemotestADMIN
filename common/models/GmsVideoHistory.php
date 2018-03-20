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
 * @property integer $video_key
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
            [['pls_id', 'video_key'], 'integer'],
            [['device_id', 'created_at', 'last_at'], 'string', 'max' => 255],
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
            'device_id' => 'Устройство',
            'created_at' => 'Начало',
            'last_at' => 'Окончание',
            'video_key' => 'Видео',
            'sender_name' => 'Отделение',
            'pls_name' => 'Плейлист',
            'region_id' => 'Регион',
            'date_at' => 'Период воспр.'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceModel()
    {
        return $this->hasOne(GmsDevices::className(), ['device' => 'device_id']);
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