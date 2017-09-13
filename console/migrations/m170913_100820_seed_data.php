<?php

use common\models\User;
use yii\db\Migration;

class m170913_100820_seed_data extends Migration
{
    public function init()
    {
        $this->db = 'Localdb';
        parent::init();
    }

    public function safeUp()
    {
        $this->insert('{{%user}}', [
            'id' => 1,
            'username' => 'admin',
            'email' => 'admin@gemotest.ru',
            'password_hash' => Yii::$app->getSecurity()->generatePasswordHash('itrTest'),
            'auth_key' => Yii::$app->getSecurity()->generateRandomString(),
            //'access_token' => Yii::$app->getSecurity()->generateRandomString(40),
            'status' => User::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time()
        ]);

        $this->insert('{{%user_profile}}', [
            'user_id'=>1,
            'locale'=>Yii::$app->sourceLanguage,
            'firstname' => 'John',
            'lastname' => 'Doe'
        ]);
    }

    public function safeDown()
    {
        $this->delete('{{%user_profile}}', [
            'user_id' => [1]
        ]);

        $this->delete('{{%user}}', [
            'id' => [1]
        ]);
    }
}