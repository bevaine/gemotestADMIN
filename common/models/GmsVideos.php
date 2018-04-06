<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gms_videos".
 *
 * @property integer $id
 * @property string $name
 * @property string $file
 * @property integer $type
 * @property integer $time
 * @property integer $created_at
 * @property string $comment
 * @property string $thumbnail
 * @property float $frame_rate
 * @property integer $nb_frames
 */
class GmsVideos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gms_videos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'file'], 'required'],
            [['time', 'nb_frames', 'created_at'], 'integer'],
            [['frame_rate'], 'safe'],
            [['name', 'file'], 'string', 'max' => 255],
            [['comment', 'thumbnail', 'type'], 'string']
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
            'file' => 'Файл',
            'type' => 'Тип',
            'time' => 'Продолжительность',
            'created_at' => 'Дата добавления',
            'comment' => 'Коментарий',
            'thumbnail' => 'thumbnail',
            'frame_rate' => 'Частота кадров',
            'nb_frames' => 'Всего кадров'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function getVideosTree()
    {
        $modules = [];
        foreach (self::find()->orderBy(['name' => 'asc'])->all() as $model) {
            /** @var $model GmsVideos */
            $file_name = basename($model->file);
            $modules[] = [
                'key' => $model->id,
                'title' => $model->name,
                'icon' => '../../img/video1.png',
                'data' => [
                    'frame_rate' => $model->frame_rate,
                    'nb_frames' => $model->nb_frames,
                    'duration' => $model->time,
                    'file' => $file_name
                ]
            ];
        }
        return $modules;
    }
}
