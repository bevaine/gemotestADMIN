<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180530_103424_add_field_gms_videos
 */
class m180530_103424_add_field_gms_videos extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%gms_videos}}', 'width', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_videos}}', 'height', Schema::TYPE_INTEGER);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_videos}}', 'width');
        $this->dropColumn('{{%gms_videos}}', 'height');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180530_103424_add_field_gms_videos cannot be reverted.\n";

        return false;
    }
    */
}
