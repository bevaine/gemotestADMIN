<?php

use yii\db\Migration;
use yii\db\Schema;
use budyaga\users\Module;


/**
 * Class m180213_120827_gms_new_table_playlist_detail
 */
class m180213_120827_gms_new_table_playlist_detail extends Migration
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
        $this->createTable('{{%gms_playlist_detail}}', [
            'id' => Schema::TYPE_PK,
            'playlist_id' => Schema::TYPE_INTEGER,
            'video_id' => Schema::TYPE_INTEGER,
            'rating' => Schema::TYPE_INTEGER,
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%gms_playlist_detail}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180213_120827_gms_new_table_playlist_detail cannot be reverted.\n";

        return false;
    }
    */
}
