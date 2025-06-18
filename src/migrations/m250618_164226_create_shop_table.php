<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop}}`.
 */
class m250618_164226_create_shop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shop}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'slug' => $this->string()->notNull()->unique(),
            'logo_id' => $this->string(),
            'owner_id' => $this->integer()->notNull(),
            'status' => $this->string()->notNull()->defaultValue('DRAFT'),
            'is_approved' => $this->boolean()->notNull()->defaultValue(false),
            'is_published' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->null(),
        ]);

        $this->addForeignKey(
            'fk-shop-owner_id-user-id',
            '{{%shop}}',
            'owner_id',
            '{{%user}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-shop-owner_id-user-id', '{{%shop}}');
        $this->dropTable('{{%shop}}');
    }
}
