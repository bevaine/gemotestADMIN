<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "DirectorFlo".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $phoneNumber
 * @property string $email
 * @property integer $passReplaced
 * @property string $login
 * @property string $password
 */
class DirectorFlo extends \yii\db\ActiveRecord
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
        return 'DirectorFlo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name'], 'required'],
            [['first_name', 'middle_name', 'last_name', 'phoneNumber', 'email', 'login', 'password'], 'string'],
            [['passReplaced'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            'phoneNumber' => 'Phone Number',
            'email' => 'Email',
            'passReplaced' => 'Pass Replaced',
            'login' => 'Login',
            'password' => 'Password',
        ];
    }
}
