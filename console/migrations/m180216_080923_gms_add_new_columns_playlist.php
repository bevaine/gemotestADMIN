<?php

use yii\db\Migration;
use yii\db\Schema;
use budyaga\users\Module;

/**
 * Class m180216_080923_gms_add_new_columns_playlist
 */
class m180216_080923_gms_add_new_columns_playlist extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%gms_playlist}}', 'jsonPlaylist', Schema::TYPE_TEXT);
        $this->addColumn('{{%gms_playlist}}', 'sender_id', Schema::TYPE_STRING . ' NOT NULL');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_playlist}}', 'jsonPlaylist');
        $this->dropColumn('{{%gms_playlist}}', 'sender_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180216_080923_gms_add_new_columns_playlist cannot be reverted.\n";

        return false;
    }
    */
}
