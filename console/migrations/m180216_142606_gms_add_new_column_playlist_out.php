<?php

use yii\db\Migration;
use yii\db\Schema;
use budyaga\users\Module;

/**
 * Class m180216_142606_gms_add_new_column_playlist_out
 */
class m180216_142606_gms_add_new_column_playlist_out extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%gms_playlist_out}}', 'is_monday', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'is_tuesday', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'is_wednesday', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'is_thursday', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'is_friday', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'is_saturday', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'is_sunday', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'time_start', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'time_end', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'date_start', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'date_end', Schema::TYPE_INTEGER);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_playlist_out}}', 'is_monday');
        $this->dropColumn('{{%gms_playlist_out}}', 'is_tuesday');
        $this->dropColumn('{{%gms_playlist_out}}', 'is_wednesday');
        $this->dropColumn('{{%gms_playlist_out}}', 'is_thursday');
        $this->dropColumn('{{%gms_playlist_out}}', 'is_friday');
        $this->dropColumn('{{%gms_playlist_out}}', 'is_saturday');
        $this->dropColumn('{{%gms_playlist_out}}', 'is_sunday');
        $this->dropColumn('{{%gms_playlist_out}}', 'time_start');
        $this->dropColumn('{{%gms_playlist_out}}', 'time_end');
        $this->dropColumn('{{%gms_playlist_out}}', 'date_start');
        $this->dropColumn('{{%gms_playlist_out}}', 'date_end');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180216_142606_gms_add_new_column_playlist_out cannot be reverted.\n";

        return false;
    }
    */
}
