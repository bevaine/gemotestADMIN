<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gms_playlist_out".
 *
 * @property integer $id
 * @property string $file
 * @property integer $device_id
 * @property integer $date_play
 * @property integer $start_time_play
 * @property integer $end_time_play
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
            [['jsonPlaylist'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
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
}
