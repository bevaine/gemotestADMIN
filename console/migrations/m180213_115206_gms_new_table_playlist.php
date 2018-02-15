<?php

use yii\db\Migration;
use yii\db\Schema;
use budyaga\users\Module;


/**
 * Class m180213_115206_gms_new_table_playlist
 */
class m180213_115206_gms_new_table_playlist extends Migration
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
        $this->createTable('{{%gms_playlist}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'file' => Schema::TYPE_STRING . ' NOT NULL',
            'type' => Schema::TYPE_INTEGER,
            'region' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%gms_playlist}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180213_115206_gms_new_table_playlist cannot be reverted.\n";

        return false;
    }
    */
}
