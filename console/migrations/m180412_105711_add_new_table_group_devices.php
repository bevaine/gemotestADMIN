<?php

use yii\db\Migration;
use yii\db\Schema;
use budyaga\users\Module;

/**
 * Class m180412_105711_add_new_table_group_devices
 */
class m180412_105711_add_new_table_group_devices extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Module::registerTranslations();

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        //таблица user
        $this->createTable('{{%gms_group_devices}}', [
            'id' => Schema::TYPE_PK,
            'group_name' => Schema::TYPE_STRING . ' NOT NULL',
            'device_id' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%gms_group_devices}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180412_105711_add_new_table_group_devices cannot be reverted.\n";

        return false;
    }
    */
}
