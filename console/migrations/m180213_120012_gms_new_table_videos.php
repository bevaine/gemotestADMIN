<?php

use yii\db\Migration;
use yii\db\Schema;
use budyaga\users\Module;

/**
 * Class m180213_120012_gms_new_table_videos
 */
class m180213_120012_gms_new_table_videos extends Migration
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
        $this->createTable('{{%gms_videos}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'file' => Schema::TYPE_STRING . ' NOT NULL',
            'type' => Schema::TYPE_INTEGER,
            'time' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_INTEGER
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%gms_videos}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180213_120012_gms_new_table_videos cannot be reverted.\n";

        return false;
    }
    */
}
