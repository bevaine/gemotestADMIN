<?php

namespace common\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "news_blog".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $content
 * @property string $publiched_at
 *
 * @property User $user
 */
class NewsBlog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news_blog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['title', 'content', 'publiched_at', 'user_id'], 'required'],
            //[['publiched_at'], 'safe'],
            [['title', 'content'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            //['publiched_at', 'default', 'value' => new Expression('NOW()')],
            ['user_id', 'default', 'value' => Yii::$app->user->id],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'title' => 'Заголовок',
            'content' => 'Новость',
            'publiched_at' => 'Дата',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
