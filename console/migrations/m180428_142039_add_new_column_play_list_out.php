<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180428_142039_add_new_column_play_list_out
 */
class m180428_142039_add_new_column_play_list_out extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%gms_playlist_out}}', 'update_json', Schema::TYPE_STRING);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_playlist_out}}', 'update_json');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180428_142039_add_new_column_play_list_out cannot be reverted.\n";

        return false;
    }
    */
}
