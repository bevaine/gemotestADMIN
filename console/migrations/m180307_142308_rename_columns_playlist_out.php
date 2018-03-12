<?php

use yii\db\Migration;

/**
 * Class m180307_142308_rename_columns_playlist_out
 */
class m180307_142308_rename_columns_playlist_out extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->renameColumn('{{%gms_playlist_out}}', 'dateStart', 'date_start');
        $this->renameColumn('{{%gms_playlist_out}}', 'dateEnd', 'date_end');
        $this->renameColumn('{{%gms_playlist_out}}', 'timeStart', 'time_start');
        $this->renameColumn('{{%gms_playlist_out}}', 'timeEnd', 'time_end');

        $this->renameColumn('{{%gms_playlist_out}}', 'isMonday', 'is_monday');
        $this->renameColumn('{{%gms_playlist_out}}', 'isTuesday', 'is_tuesday');
        $this->renameColumn('{{%gms_playlist_out}}', 'isWednesday', 'is_wednesday');
        $this->renameColumn('{{%gms_playlist_out}}', 'isThursday', 'is_thursday');
        $this->renameColumn('{{%gms_playlist_out}}', 'isFriday', 'is_friday');
        $this->renameColumn('{{%gms_playlist_out}}', 'isSaturday', 'is_saturday');
        $this->renameColumn('{{%gms_playlist_out}}', 'isSunday', 'is_sunday');

        $this->dropColumn('{{%gms_playlist_out}}', 'date_play');
        $this->dropColumn('{{%gms_playlist_out}}', 'start_play_time');
        $this->dropColumn('{{%gms_playlist_out}}', 'end_play_time');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameColumn('{{%gms_playlist_out}}', 'date_start', 'dateStart');
        $this->renameColumn('{{%gms_playlist_out}}', 'date_end', 'dateEnd');
        $this->renameColumn('{{%gms_playlist_out}}', 'time_start', 'timeStart');
        $this->renameColumn('{{%gms_playlist_out}}', 'time_end', 'timeEnd');

        $this->renameColumn('{{%gms_playlist_out}}', 'is_monday', 'isMonday');
        $this->renameColumn('{{%gms_playlist_out}}', 'is_tuesday', 'isTuesday');
        $this->renameColumn('{{%gms_playlist_out}}', 'is_wednesday', 'isWednesday');
        $this->renameColumn('{{%gms_playlist_out}}', 'is_thursday', 'isThursday');
        $this->renameColumn('{{%gms_playlist_out}}', 'is_friday', 'isFriday');
        $this->renameColumn('{{%gms_playlist_out}}', 'is_saturday', 'isSaturday');
        $this->renameColumn('{{%gms_playlist_out}}', 'is_sunday', 'isSunday');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180307_142308_rename_columns_playlist_out cannot be reverted.\n";

        return false;
    }
    */
}
