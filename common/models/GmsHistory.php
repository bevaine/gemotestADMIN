<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gms_history".
 *
 * @property integer $id
 * @property integer $pls_id
 * @property integer $device_id
 * @property integer $created_at
 * @property integer $status
 * @property string $log_text
 * @property GmsPlaylistOut $playlistOutModel
 */
class GmsHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gms_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pls_id', 'status'], 'integer'],
            [['device_id', 'log_text', 'created_at'], 'string'],
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->created_at = time();
        }
        return parent::beforeSave($insert);
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
            'created_at' => 'Дата события',
            'status' => 'Статус',
            'log_text' => 'События',
            'pls_name' => 'Плейлист'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaylistOutModel()
    {
        return $this->hasOne(GmsPlaylistOut::className(), ['id' => 'pls_id']);
    }

    /**
     * @param null $id
     * @return array|mixed
     */
    public static function getStatusTypeArray($id = null) {
        $arr =  ['0' => 'Ошибка', '1' => 'Не изменился', '2' => 'Изменился', '3' => 'Нет подход. плейлистов'];
        return is_null($id) ? $arr : $arr[$id];
    }
}
