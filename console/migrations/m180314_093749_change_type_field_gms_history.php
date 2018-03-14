<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180314_093749_change_type_field_gms_history
 */
class m180314_093749_change_type_field_gms_history extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('{{%gms_history}}', 'device_id', Schema::TYPE_STRING);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('{{%gms_history}}', 'device_id', Schema::TYPE_INTEGER);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180314_093749_change_type_field_gms_history cannot be reverted.\n";

        return false;
    }
    */
}
