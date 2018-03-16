<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180316_060556_add_column_table_devices
 */
class m180316_060556_add_column_table_devices extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%gms_devices}}', 'timezone', Schema::TYPE_STRING);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_devices}}', 'timezone');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180316_060556_add_column_table_devices cannot be reverted.\n";

        return false;
    }
    */
}
