<?php

use yii\db\Migration;
use yii\db\Schema;
use budyaga\users\models\User;
use yii\rbac\Item;
use budyaga\users\Module;

/**
 * Class m180206_083412_new_table_erp_groups
 */
class m180206_083412_new_table_erp_groups extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        Module::registerTranslations();

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        //таблица user
        $this->createTable('{{%erp_groups_relations}}', [
            'id' => Schema::TYPE_PK,
            'department' => Schema::TYPE_STRING . ' NOT NULL',
            'group' => Schema::TYPE_STRING . ' NULL DEFAULT NULL',
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%erp_groups_relations}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180206_083412_new_table_erp_groups cannot be reverted.\n";

        return false;
    }
    */
}
