<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180402_095633_add_new_column_playlist_out
 */
class m180402_095633_add_new_column_playlist_out extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%gms_playlist_out}}', 'jsonKodi', Schema::TYPE_TEXT);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_devices}}', 'jsonKodi');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180402_095633_add_new_column_playlist_out cannot be reverted.\n";

        return false;
    }
    */
}
