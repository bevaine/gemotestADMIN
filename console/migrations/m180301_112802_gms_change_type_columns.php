<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180301_112802_gms_change_type_columns
 */
class m180301_112802_gms_change_type_columns extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('{{%gms_playlist_out}}', 'file', 'DROP NOT NULL');
        $this->addColumn('{{%gms_playlist_out}}', 'name', Schema::TYPE_STRING);

        $this->dropColumn('{{%gms_devices}}', 'auth_status');
        $this->addColumn('{{%gms_devices}}', 'auth_status', $this->integer()->defaultValue(0));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_playlist_out}}', 'name');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180301_112802_gms_change_type_columns cannot be reverted.\n";

        return false;
    }
    */
}
