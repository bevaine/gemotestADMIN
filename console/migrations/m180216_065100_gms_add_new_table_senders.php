<?php

use yii\db\Migration;
use yii\db\Schema;
use budyaga\users\Module;

/**
 * Class m180216_065100_gms_add_new_table_senders
 */
class m180216_065100_gms_add_new_table_senders extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        Module::registerTranslations();

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        //таблица user
        $this->createTable('{{%gms_senders}}', [
            'id' => Schema::TYPE_PK,
            'sender_id' => Schema::TYPE_STRING . ' NOT NULL',
            'sender_name' => Schema::TYPE_STRING . ' NOT NULL',
            'region_id' => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%gms_senders}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180216_065100_gms_add_new_table_senders cannot be reverted.\n";

        return false;
    }
    */
}
