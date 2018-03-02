<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180301_061309_gms_add_columns_devices
 */
class m180301_061309_gms_add_columns_devices extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%gms_devices}}', 'region_id', Schema::TYPE_INTEGER);
        $this->addColumn('{{%gms_devices}}', 'auth_status', Schema::TYPE_INTEGER);

        $this->renameColumn('{{%gms_devices}}', 'device_id', 'device');
        $this->renameColumn('{{%gms_devices}}', 'updated_at', 'last_active_at');

        $this->dropColumn('{{%gms_devices}}', 'playlist');
        $this->addColumn('{{%gms_devices}}', 'current_pls_id', Schema::TYPE_INTEGER);

        $this->alterColumn('{{%gms_devices}}', 'sender_id', 'DROP NOT NULL');
        $this->alterColumn('{{%gms_devices}}', 'host_name', 'DROP NOT NULL');
        $this->alterColumn('{{%gms_devices}}', 'device', 'DROP NOT NULL');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_devices}}', 'region_id');
        $this->dropColumn('{{%gms_devices}}', 'auth_status');

        $this->renameColumn('{{%gms_devices}}', 'device', 'device_id');
        $this->renameColumn('{{%gms_devices}}', 'last_active_at', 'updated_at');

        $this->addColumn('{{%gms_devices}}', 'playlist', Schema::TYPE_STRING);
        $this->dropColumn('{{%gms_devices}}', 'current_pls_id');

        $this->alterColumn('{{%gms_devices}}', 'sender_id', 'SET NOT NULL');
        $this->alterColumn('{{%gms_devices}}', 'host_name', 'SET NOT NULL');
        $this->alterColumn('{{%gms_devices}}', 'device', 'SET NOT NULL');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180301_061309_gms_add_columns_devices cannot be reverted.\n";

        return false;
    }
    */
}
