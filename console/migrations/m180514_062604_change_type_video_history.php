<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180514_062604_change_type_video_history
 */
class m180514_062604_change_type_video_history extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //$this->alterColumn('{{%gms_video_history}}', 'device_id', Schema::TYPE_INTEGER);
        $this->execute("ALTER TABLE gms_video_history ALTER COLUMN device_id TYPE integer USING NULL");
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('{{%gms_video_history}}', 'device_id', Schema::TYPE_STRING);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180514_062604_change_type_video_history cannot be reverted.\n";

        return false;
    }
    */
}
