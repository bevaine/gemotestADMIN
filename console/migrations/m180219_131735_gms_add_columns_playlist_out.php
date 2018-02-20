<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180219_131735_gms_add_columns_playlist_out
 */
class m180219_131735_gms_add_columns_playlist_out extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%gms_playlist_out}}', 'sender_id', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'region_id', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'jsonPlaylist', Schema::TYPE_TEXT);
        $this->addColumn('{{%gms_playlist_out}}', 'created_at', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'active', Schema::TYPE_INTEGER);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_playlist_out}}', 'sender_id');
        $this->dropColumn('{{%gms_playlist_out}}', 'region_id');
        $this->dropColumn('{{%gms_playlist_out}}', 'jsonPlaylist');
        $this->dropColumn('{{%gms_playlist_out}}', 'created_at');
        $this->dropColumn('{{%gms_playlist_out}}', 'active');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180219_131735_gms_add_columns_playlist_out cannot be reverted.\n";

        return false;
    }
    */
}
