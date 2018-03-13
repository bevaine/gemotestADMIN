<?php

use yii\db\Migration;
use yii\db\Schema;
use budyaga\users\Module;

/**
 * Class m180313_131616_new_table_gms_history
 */
class m180313_131616_new_table_gms_history extends Migration
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
        $this->createTable('{{%gms_history}}', [
            'id' => Schema::TYPE_PK,
            'pls_id' => Schema::TYPE_INTEGER,
            'device_id' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_INTEGER,
            'status' => Schema::TYPE_INTEGER,
            'log_text' => Schema::TYPE_TEXT
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%gms_history}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180313_131616_new_table_gms_history cannot be reverted.\n";

        return false;
    }
    */
}
