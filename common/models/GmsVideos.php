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
 *
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
            [['time', 'created_at'], 'integer'],
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
            'thumbnail' => 'thumbnail'
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
                'data' => [
                    'duration' => $model->time,
                    'file' => $file_name
                ]
            ];
        }
        return $modules;
    }
}
