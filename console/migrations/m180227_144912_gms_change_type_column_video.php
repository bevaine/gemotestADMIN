<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180227_144912_gms_change_type_column_video
 */
class m180227_144912_gms_change_type_column_video extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('{{%gms_videos}}', 'type', Schema::TYPE_STRING);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('{{%gms_videos}}', 'type', Schema::TYPE_INTEGER);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180227_144912_gms_change_type_column_video cannot be reverted.\n";

        return false;
    }
    */
}
