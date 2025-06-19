<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tag}}`.
 */
class m250619_101715_create_tag_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tag}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'slug' => $this->string()->notNull()->unique(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%product_tag}}', [
            'id' => $this->primaryKey(),
            'tag_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-product_tag-tagId', 'product_tag', 'tag_id');
        $this->createIndex('idx-product_tag-productId', 'product_tag', 'product_id');

        $this->addForeignKey(
            'fk-product_tag-tag',
            'product_tag',
            'tag_id',
            'tag',
            'id',
        );

        $this->addForeignKey(
            'fk-product_tag-product',
            'product_tag',
            'product_id',
            'product',
            'id',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tag}}');
        $this->dropForeignKey('fk-product_tag-product', 'product_tag');
        $this->dropForeignKey('fk-product_tag-tag', 'product_tag');
        $this->dropTable('{{%product_tag}}');
    }
}
