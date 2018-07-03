<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "n_kkm_users".
 *
 * @property int $id
 * @property int $kkm_id
 * @property int $user_id
 * @property string $login
 * @property string $password
 * @property int $user_type
 * @property nkkm $kkm
 * @property nkkm $kkmMany
 * @property Logins $logins
 */
class NKkmUsers extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'n_kkm_users';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('GemoTestDB');
    }


    /**
     * @return array
     */
    public static function PrimaryKey()
    {
        return ['id'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kkm_id', 'user_id'], 'integer'],
            [['user_id', 'login', 'password', 'user_type'], 'required'],
            [['login', 'password', 'user_type'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kkm_id' => 'Kkm ID',
            'user_id' => 'User ID',
            'login' => 'Login',
            'password' => 'Password',
            'user_type' => 'User Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKkm()
    {
        return $this->hasOne(nkkm::class, ['id' => 'kkm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogins()
    {
        return $this->hasOne(Logins::class, ['aid' => 'user_id']);
    }

    /**
     * @inheritdoc
     */
    public static function getSenderList()
    {
        $arr = self::find()
            ->distinct()
            ->joinWith(['kkm'])
            ->select(['sender_key', 'kkm_id'])
            ->where(['is not', 'sender_key', null])
            ->orderBy(['sender_key' => 'asc'])
            ->asArray()
            ->all();
        return ArrayHelper::map($arr,'sender_key','sender_key');
    }
}