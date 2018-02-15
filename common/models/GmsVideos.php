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
            [['type', 'time', 'created_at'], 'integer'],
            [['name', 'file'], 'string', 'max' => 255],
            [['comment'], 'string']
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
            'comment' => 'Коментарий'
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
            $modules[] = ['key' => $model->id, 'title' => $model->name];
        }
        return $modules;
    }
}
