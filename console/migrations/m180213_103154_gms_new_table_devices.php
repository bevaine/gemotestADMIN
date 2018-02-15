<?php

use yii\db\Migration;
use yii\db\Schema;
use budyaga\users\Module;


/**
 * Class m180213_103154_gms_new_table_devices
 */
class m180213_103154_gms_new_table_devices extends Migration
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
        $this->createTable('{{%gms_devices}}', [
            'id' => Schema::TYPE_PK,
            'sender_id' => Schema::TYPE_STRING . ' NOT NULL',
            'host_name' => Schema::TYPE_STRING . ' NOT NULL',
            'device_id' => Schema::TYPE_STRING . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'playlist' => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%gms_devices}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180213_103154_gms_new_table_devices cannot be reverted.\n";

        return false;
    }
    */
}
