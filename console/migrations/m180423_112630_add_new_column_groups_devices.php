<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180423_112630_add_new_column_groups_devices
 */
class m180423_112630_add_new_column_groups_devices extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%gms_playlist}}', 'group_id', Schema::TYPE_INTEGER);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_playlist}}', 'group_id');
    }
}
