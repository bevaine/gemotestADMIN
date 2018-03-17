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
        ];
    }
}
