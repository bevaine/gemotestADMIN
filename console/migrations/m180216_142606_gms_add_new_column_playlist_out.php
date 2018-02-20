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
        $this->addColumn('{{%gms_playlist_out}}', 'isMonday', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'isTuesday', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'isWednesday', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'isThursday', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'isFriday', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'isSaturday', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'isSunday', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'timeStart', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'timeEnd', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'dateStart', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_playlist_out}}', 'dateEnd', Schema::TYPE_INTEGER);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_playlist_out}}', 'isMonday');
        $this->dropColumn('{{%gms_playlist_out}}', 'isTuesday');
        $this->dropColumn('{{%gms_playlist_out}}', 'isWednesday');
        $this->dropColumn('{{%gms_playlist_out}}', 'isThursday');
        $this->dropColumn('{{%gms_playlist_out}}', 'isFriday');
        $this->dropColumn('{{%gms_playlist_out}}', 'isSaturday');
        $this->dropColumn('{{%gms_playlist_out}}', 'isSunday');
        $this->dropColumn('{{%gms_playlist_out}}', 'timeStart');
        $this->dropColumn('{{%gms_playlist_out}}', 'timeEnd');
        $this->dropColumn('{{%gms_playlist_out}}', 'dateStart');
        $this->dropColumn('{{%gms_playlist_out}}', 'dateEnd');
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
