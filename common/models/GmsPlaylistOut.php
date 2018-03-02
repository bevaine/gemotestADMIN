<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "gms_playlist_out".
 *
 * @property integer $id
 * @property string $name
 * @property integer $device_id
 * @property integer $date_play
 * @property integer $start_time_play
 * @property integer $end_time_play
 * @property GmsRegions $regionModel
 * @property GmsSenders $senderModel
 */
class GmsPlaylistOut extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gms_playlist_out';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['region_id', 'jsonPlaylist', 'dateStart', 'dateEnd', 'timeStart', 'timeEnd'], 'required'],
            [['region_id', 'sender_id', 'device_id', 'isMonday', 'isTuesday', 'isWednesday', 'isThursday', 'isFriday', 'isSaturday', 'isSunday', 'timeStart', 'timeEnd', 'dateStart', 'dateEnd'], 'integer'],
            [['name', 'jsonPlaylist'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'region_id' => 'Регион',
            'sender_id' => 'Отделение',
            'device_id' => 'Устройство',
            'dateStart' => 'Дата старта',
            'dateEnd' => 'Дата окончания',
            'timeStart' => 'Время старта',
            'timeEnd' => 'Время окончания',
            'jsonPlaylist' => 'Плейлист',
            'active' => 'Активный',
            'created_at' => 'Дата создания'
        ];
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getPlayListArray() {

        $arr = self::find()
            ->orderBy(['name' => 'asc'])
            ->asArray()
            ->all();

        return ArrayHelper::map($arr,'id','name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegionModel()
    {
        return $this->hasOne(GmsRegions::className(), ['id' => 'region_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSenderModel()
    {
        return $this->hasOne(GmsSenders::className(), ['id' => 'sender_id']);
    }
}
