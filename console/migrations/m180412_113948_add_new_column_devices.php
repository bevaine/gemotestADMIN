<?php

use yii\db\Migration;

/**
 * Class m180412_113948_add_new_column_devices
 */
class m180412_113948_add_new_column_devices extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%gms_devices}}', 'host_name', 'name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{%gms_devices}}', 'name', 'host_name');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180412_113948_add_new_column_devices cannot be reverted.\n";

        return false;
    }
    */
}
