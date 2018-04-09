<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180409_134922_add_new_colums_video_history
 */
class m180409_134922_add_new_colums_video_history extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%gms_video_history}}', 'pls_pos', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_video_history}}', 'pls_guid', Schema::TYPE_STRING);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_video_history}}', 'pls_pos');
        $this->dropColumn('{{%gms_video_history}}', 'pls_guid');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180409_134922_add_new_colums_video_history cannot be reverted.\n";

        return false;
    }
    */
}
