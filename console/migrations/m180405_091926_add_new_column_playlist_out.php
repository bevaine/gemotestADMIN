<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180405_091926_add_new_column_playlist_out
 */
class m180405_091926_add_new_column_playlist_out extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%gms_playlist_out}}', 'update_at', Schema::TYPE_INTEGER);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_playlist_out}}', 'update_at');
    }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180405_091926_add_new_column_playlist_out cannot be reverted.\n";

        return false;
    }
    */
}
