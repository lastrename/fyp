<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product}}`.
 */
class m250618_170704_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'price' => $this->float()->notNull(),
            'stock' => $this->integer()->notNull(),
            'status' => $this->string()->notNull()->defaultValue('DRAFT'),
            'category_id' => $this->integer()->notNull(),
            'shop_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->null(),
        ]);

        // Внешние ключи
        $this->addForeignKey(
            'fk-product-category_id',
            '{{%product}}',
            'category_id',
            '{{%category}}',
            'id',
        );

        $this->addForeignKey(
            'fk-product-shop_id',
            '{{%product}}',
            'shop_id',
            '{{%shop}}',
            'id',
        );

        $this->addForeignKey(
            'fk-product-user_id',
            '{{%product}}',
            'user_id',
            '{{%user}}',
            'id',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-product-category_id', '{{%product}}');
        $this->dropForeignKey('fk-product-shop_id', '{{%product}}');
        $this->dropForeignKey('fk-product-user_id', '{{%product}}');
        $this->dropTable('{{%product}}');
    }
}
