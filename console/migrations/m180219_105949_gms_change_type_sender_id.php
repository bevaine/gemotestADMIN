<?php

use yii\db\Migration;
use yii\db\Schema;
use budyaga\users\Module;

/**
 * Class m180219_105949_gms_change_type_sender_id
 */
class m180219_105949_gms_change_type_sender_id extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('{{%gms_senders}}', 'sender_id', 'sender_key');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameColumn('{{%gms_senders}}', 'sender_key', 'sender_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180219_105949_gms_change_type_sender_id cannot be reverted.\n";

        return false;
    }
    */
}
