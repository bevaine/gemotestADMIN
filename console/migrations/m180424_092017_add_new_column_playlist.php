<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180424_092017_add_new_column_playlist
 */
class m180424_092017_add_new_column_playlist extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%gms_playlist}}', 'device_id', Schema::TYPE_INTEGER);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_playlist}}', 'device_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180424_092017_add_new_column_playlist cannot be reverted.\n";

        return false;
    }
    */
}
