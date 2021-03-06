<?php

use yii\db\Migration;
use yii\db\Schema;
use budyaga\users\Module;

/**
 * Class m180213_123612_gms_new_table_playlist_detail_out
 */
class m180213_123612_gms_new_table_playlist_detail_out extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        Module::registerTranslations();

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        //таблица user
        $this->createTable('{{%gms_playlist_detail_out}}', [
            'id' => Schema::TYPE_PK,
            'playlist_id' => Schema::TYPE_INTEGER,
            'video_id' => Schema::TYPE_INTEGER,
            'rating' => Schema::TYPE_INTEGER,
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%gms_playlist_detail_out}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180213_123612_gms_new_table_playlist_detail_out cannot be reverted.\n";

        return false;
    }
    */
}
