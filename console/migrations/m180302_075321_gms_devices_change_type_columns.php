<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180302_075321_gms_devices_change_type_columns
 */
class m180302_075321_gms_devices_change_type_columns extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('{{%gms_devices}}', 'sender_id');
        $this->addColumn('{{%gms_devices}}', 'sender_id', Schema::TYPE_INTEGER);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_devices}}', 'sender_id');
        $this->addColumn('{{%gms_devices}}', 'sender_id', Schema::TYPE_STRING);

    }
}
