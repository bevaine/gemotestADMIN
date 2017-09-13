<?php

use yii\db\Migration;

class m170913_070119_create_table_user_profile extends Migration
{
    public function init()
    {
        $this->db = 'Localdb';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_profile}}', [
            'user_id' => $this->primaryKey(),
            'firstname' => $this->string(),
            'middlename' => $this->string(),
            'lastname' => $this->string(),
            'avatar_path' => $this->string(),
            'avatar_base_url' => $this->string(),
            'locale' => $this->string(32)->notNull(),
            'gender' => $this->smallInteger(1)
        ], $tableOptions);

        $this->addForeignKey('fk_user', '{{%user_profile}}', 'user_id', '{{%user}}', 'id', 'cascade', 'cascade');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_user', '{{%user_profile}}');
        $this->dropTable('{{%user_profile}}');
    }
}