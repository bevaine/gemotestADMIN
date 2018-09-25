<?php

use yii\db\Migration;
use yii\db\Schema;
use budyaga\users\Module;

/**
 * Class m180911_072148_create_new_table_roles
 */
class m180911_072148_create_new_table_roles extends Migration
{
    public function safeUp()
    {
        Module::registerTranslations();

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%skynet_roles}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING,
            'type' => Schema::TYPE_INTEGER,
            'structure_json' => Schema::TYPE_TEXT,
            'tables_json' => Schema::TYPE_TEXT,
            'info_json' => Schema::TYPE_TEXT,
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%skynet_roles}}');
    }
}
