<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180520_093405_change_type_video_history
 */
class m180520_093405_change_type_video_history extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE gms_video_history ALTER COLUMN created_at TYPE integer USING NULL");
        $this->execute("ALTER TABLE gms_video_history ALTER COLUMN last_at TYPE integer USING NULL");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%gms_video_history}}', 'created_at', Schema::TYPE_STRING);
        $this->alterColumn('{{%gms_video_history}}', 'last_at', Schema::TYPE_STRING);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180520_093405_change_type_video_history cannot be reverted.\n";

        return false;
    }
    */
}
