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
            [['file'], 'required'],
            [['device_id', 'date_play', 'start_time_play', 'end_time_play'], 'integer'],
            [['file'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file' => 'File',
            'device_id' => 'Device ID',
            'date_play' => 'Date Play',
            'start_time_play' => 'Start Time Play',
            'end_time_play' => 'End Time Play',
        ];
    }
}
