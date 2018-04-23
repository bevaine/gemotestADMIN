<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180413_132139_add_new_column_groups_devices
 */
class m180413_132139_add_new_column_groups_devices extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%gms_group_devices}}', 'group_id', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_group_devices}}', 'parent_key', Schema::TYPE_STRING);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_group_devices}}', 'group_id');
        $this->dropColumn('{{%gms_group_devices}}', 'parent_key');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180413_132139_add_new_column_groups_devices cannot be reverted.\n";

        return false;
    }
    */
}
