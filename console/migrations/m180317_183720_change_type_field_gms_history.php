<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180317_183720_change_type_field_gms_history
 */
class m180317_183720_change_type_field_gms_history extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('{{%gms_history}}', 'created_at', Schema::TYPE_STRING);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('{{%gms_history}}', 'created_at', Schema::TYPE_INTEGER);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180317_183720_change_type_field_gms_history cannot be reverted.\n";

        return false;
    }
    */
}
