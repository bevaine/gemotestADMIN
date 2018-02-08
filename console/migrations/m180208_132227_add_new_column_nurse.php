<?php

use yii\db\Migration;

/**
 * Class m180208_132227_add_new_column_nurse
 */
class m180208_132227_add_new_column_nurse extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%erp_groups_relations}}', 'nurse', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%erp_groups_relations}}', 'nurse');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180208_132227_add_new_column_nurse cannot be reverted.\n";

        return false;
    }
    */
}
