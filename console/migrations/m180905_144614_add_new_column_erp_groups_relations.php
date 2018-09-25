<?php


use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180905_144614_add_new_column_erp_groups_relations
 */
class m180905_144614_add_new_column_erp_groups_relations extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%erp_groups_relations}}', 'mis_access', Schema::TYPE_STRING);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%erp_groups_relations}}', 'mis_access');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180905_144614_add_new_column_erp_groups_relations cannot be reverted.\n";

        return false;
    }
    */
}
