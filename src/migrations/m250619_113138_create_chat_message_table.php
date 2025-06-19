<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%chat_message}}`.
 */
class m250619_113138_create_chat_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chat_message}}', [
            'id' => $this->primaryKey(),
            'chat_id' => $this->integer()->notNull(),
            'message_type' => $this->string()->notNull()->defaultValue('text'),
            'content' => $this->text()->notNull(),
            'result' => $this->json(),
            'sender_type' => $this->integer()->notNull()->defaultValue(1),
            'is_read' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-chat_message-chat_id',
            'chat_message',
            'chat_id'
        );

        $this->createIndex(
            '{{%idx-chat_message-sender_type}}',
            '{{%chat_message}}',
            'sender_type'
        );

        $this->createIndex(
            '{{%idx-chat_message-is_read}}',
            '{{%chat_message}}',
            'is_read'
        );

        $this->createIndex(
            '{{%idx-chat_message-message_type}}',
            '{{%chat_message}}',
            'message_type'
        );

        // Составной индекс для быстрого поиска непрочитанных сообщений конкретного чата
        $this->createIndex(
            '{{%idx-chat_message-chat_id-is_read-sender_type}}',
            '{{%chat_message}}',
            ['chat_id', 'is_read', 'sender_type']
        );

        // Составной индекс для сортировки сообщений по времени в рамках чата
        $this->createIndex(
            '{{%idx-chat_message-chat_id-created_at}}',
            '{{%chat_message}}',
            ['chat_id', 'created_at']
        );

        $this->addForeignKey(
            'fk-chat_message-chat',
            'chat_message',
            'chat_id',
            'chat',
            'id',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-chat_message-chat', 'chat_message');

        $this->dropIndex(
            '{{%idx-chat_message-chat_id-created_at}}',
            '{{%chat_message}}'
        );

        $this->dropIndex(
            '{{%idx-chat_message-chat_id-is_read-sender_type}}',
            '{{%chat_message}}'
        );

        $this->dropIndex(
            '{{%idx-chat_message-message_type}}',
            '{{%chat_message}}'
        );

        $this->dropIndex(
            '{{%idx-chat_message-is_read}}',
            '{{%chat_message}}'
        );

        $this->dropIndex(
            '{{%idx-chat_message-sender_type}}',
            '{{%chat_message}}'
        );

        $this->dropIndex(
            '{{%idx-chat_message-chat_id}}',
            '{{%chat_message}}'
        );

        $this->dropTable('{{%chat_message}}');
    }
}
