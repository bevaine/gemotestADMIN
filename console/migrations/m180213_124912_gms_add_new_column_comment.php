<?php

use yii\db\Migration;
use yii\db\Schema;
use budyaga\users\Module;


/**
 * Class m180213_124912_gms_add_new_column_comment
 */
class m180213_124912_gms_add_new_column_comment extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%gms_videos}}', 'comment', Schema::TYPE_TEXT);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%gms_videos}}', 'comment');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180213_124912_gms_add_new_column_comment cannot be reverted.\n";

        return false;
    }
    */
}
