<?php

use yii\db\Migration;
use yii\db\Schema;
use budyaga\users\Module;

/**
 * Class m180317_184238_new_table_gms_video_history
 */
class m180317_184238_new_table_gms_video_history extends Migration
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
        $this->createTable('{{%gms_video_history}}', [
            'id' => Schema::TYPE_PK,
            'pls_id' => Schema::TYPE_INTEGER,
            'device_id' => Schema::TYPE_STRING,
            'created_at' => Schema::TYPE_STRING,
            'last_at' => Schema::TYPE_STRING,
            'video_key' => Schema::TYPE_INTEGER,
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%gms_video_history}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180317_184238_new_table_gms_video_history cannot be reverted.\n";

        return false;
    }
    */
}
