<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "erp_users".
 *
 * @property integer $id
 * @property integer $group_id
 * @property string $name
 * @property string $login
 * @property string $password
 * @property integer $status
 * @property string $password_dt
 * @property string $skynet_login
 */
class ErpUsers extends \yii\db\ActiveRecord
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
        return 'erp_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'status'], 'required'],
            [['group_id', 'status'], 'integer'],
            [['name', 'login', 'password', 'skynet_login'], 'string'],
            [['password_dt'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Group ID',
            'name' => 'Name',
            'login' => 'Login',
            'password' => 'Password',
            'status' => 'Status',
            'password_dt' => 'Password Dt',
            'skynet_login' => 'Skynet Login',
        ];
    }
}
