<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "DirectorFloSender".
 *
 * @property integer $id
 * @property integer $director_id
 * @property string $sender_key
 * @property DirectorFlo $directorFlo
 * @property Franchazy $floName
 */
class DirectorFloSender extends \yii\db\ActiveRecord
{
    /**
     * @return null|object
     */
    public static function getDb() {
        return Yii::$app->get('GemoTestDB');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'DirectorFloSender';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['director_id', 'sender_key'], 'required'],
            [['director_id'], 'integer'],
            [['sender_key'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'director_id' => 'Director ID',
            'sender_key' => 'Sender Key',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDirectorFlo()
    {
        return $this->hasOne(DirectorFlo::className(), ['id' => 'director_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFloName()
    {
        return $this->hasOne(Franchazy::className(), ['Key' => 'sender_key']);
    }
}
