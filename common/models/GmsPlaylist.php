<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gms_playlist".
 *
 * @property integer $id
 * @property string $name
 * @property string $file
 * @property integer $type
 * @property integer $region
 * @property integer $created_at
 * @property integer $updated_at
 */
class GmsPlaylist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gms_playlist';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'file'], 'required'],
            [['type', 'region', 'created_at', 'updated_at'], 'integer'],
            [['name', 'file'], 'string', 'max' => 255],
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
            'file' => 'Имя файла',
            'type' => 'Тип плейлиста',
            'region' => 'Регион прогрывания',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата редактирования',
        ];
    }

    /**
     * @param null $id
     * @return array|mixed
     */
    public static function getPlayListType($id = null) {
        $arr =  ['1' => 'Региональный', '2' => 'Коммерческий'];
        return is_null($id) ? $arr : $arr[$id];
    }
}
