<?php

use yii\db\Migration;

/**
 * Class m180216_105748_gms_change_type_columns
 */
class m180216_105748_gms_change_type_columns extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%gms_playlist}}', 'name', 'DROP NOT NULL');
        $this->alterColumn('{{%gms_playlist}}', 'file', 'DROP NOT NULL');
    }

    public function down()
    {
        $this->alterColumn('{{%gms_playlist}}', 'name', 'SET NOT NULL');
        $this->alterColumn('{{%gms_playlist}}', 'file', 'SET NOT NULL');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180216_105748_gms_change_type_columns cannot be reverted.\n";

        return false;
    }
    */
}
