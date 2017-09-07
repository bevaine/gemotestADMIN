<?php

use yii\db\Migration;

class m170717_172242_table_news_blog extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%news_blog}}', [
            'id'   => $this->primaryKey(),
            'user_id' => $this->integer(),
            'title' => $this->string()->notNull(),
            'content' => $this->string()->notNull(),
            'publiched_at' => $this->timestamp(),
            'FOREIGN KEY (user_id) REFERENCES {{%user}} (id) ON DELETE CASCADE ON UPDATE CASCADE',
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%news_blog}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170717_172242_table_news_blog cannot be reverted.\n";

        return false;
    }
    */
}
