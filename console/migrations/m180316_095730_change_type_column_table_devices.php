<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180316_095730_change_type_column_table_devices
 */
class m180316_095730_change_type_column_table_devices extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('{{%gms_devices}}', 'created_at', Schema::TYPE_STRING);
        $this->alterColumn('{{%gms_devices}}', 'last_active_at', Schema::TYPE_STRING);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('{{%gms_devices}}', 'created_at', Schema::TYPE_INTEGER);
        $this->alterColumn('{{%gms_devices}}', 'last_active_at', Schema::TYPE_INTEGER);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180316_095730_change_type_column_table_devices cannot be reverted.\n";

        return false;
    }
    */
}
