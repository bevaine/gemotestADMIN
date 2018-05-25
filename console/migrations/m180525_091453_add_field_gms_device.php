<?php

use yii\db\Migration;
use yii\db\Schema;


/**
 * Class m180525_091453_add_field_gms_device
 */
class m180525_091453_add_field_gms_device extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%gms_devices}}', 'IP', Schema::TYPE_STRING);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_devices}}', 'IP');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180525_091453_add_field_gms_device cannot be reverted.\n";

        return false;
    }
    */
}
