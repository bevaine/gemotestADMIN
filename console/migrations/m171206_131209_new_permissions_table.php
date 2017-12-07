<?php

use yii\db\Migration;
use yii\db\Schema;
use budyaga\users\models\User;
use yii\rbac\Item;
use budyaga\users\Module;

class m171206_131209_new_permissions_table extends Migration
{
    public function safeUp()
    {
        Module::registerTranslations();

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        //таблица user
        $this->createTable('{{%permissions}}', [
            'id' => Schema::TYPE_PK,
            'department' => Schema::TYPE_STRING . ' NOT NULL',
            'permission' => Schema::TYPE_STRING . ' NULL DEFAULT NULL',
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%permissions}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171206_131209_new_permissions_table cannot be reverted.\n";

        return false;
    }
    */
}
