<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180227_072037_gms_add_column_video
 */
class m180227_072037_gms_add_column_video extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%gms_videos}}', 'thumbnail', Schema::TYPE_STRING);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_videos}}', 'thumbnail');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180227_072037_gms_add_column_video cannot be reverted.\n";

        return false;
    }
    */
}
