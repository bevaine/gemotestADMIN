<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180219_111739_gms_change_type_sender_id
 */
class m180219_111739_gms_change_type_sender_id extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('{{%gms_playlist}}', 'sender_id');
        $this->addColumn('{{%gms_playlist}}', 'sender_id', Schema::TYPE_INTEGER);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180219_111739_gms_change_type_sender_id cannot be reverted.\n";

        return false;
    }
    */
}
