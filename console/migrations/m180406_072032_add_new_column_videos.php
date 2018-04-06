<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180406_072032_add_new_column_videos
 */
class m180406_072032_add_new_column_videos extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%gms_videos}}', 'frame_rate', Schema::TYPE_FLOAT);
        $this->addColumn('{{%gms_videos}}', 'nb_frames', Schema::TYPE_INTEGER);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_videos}}', 'frame_rate');
        $this->dropColumn('{{%gms_videos}}', 'nb_frames');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180406_072032_add_new_column_videos cannot be reverted.\n";

        return false;
    }
    */
}
