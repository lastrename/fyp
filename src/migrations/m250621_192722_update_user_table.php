<?php

use yii\db\Migration;

class m250621_192722_update_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%user}}', 'update_at', 'updated_at');

        $this->addColumn('{{%user}}', 'is_social', $this->boolean()->notNull()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'is_social');
        $this->renameColumn('{{%user}}', 'updated_at', 'update_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250621_192722_update_user_table cannot be reverted.\n";

        return false;
    }
    */
}
