<?php

namespace app\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "chat_message".
 *
 * @property int $id
 * @property int $chat_id
 * @property string $message_type
 * @property string $content
 * @property string|null $result
 * @property int $sender_type
 * @property int $is_read
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Chat $chat
 */
class ChatMessage extends ActiveRecord
{
    const MESSAGE_TYPE_TEXT = 'text';
    const MESSAGE_TYPE_IMAGE = 'image';
    const MESSAGE_TYPE_FILE = 'file';
    const MESSAGE_TYPE_SYSTEM = 'system';

    const SENDER_USER = 1;
    const SENDER_BOT = 2;

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'chat_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['chat_id', 'message_type', 'content', 'sender_type'], 'required'],
            [['chat_id', 'sender_type', 'is_read', 'created_at', 'updated_at'], 'integer'],
            [['content', 'result'], 'string'],
            [['message_type'], 'string', 'max' => 255],
            [['message_type'], 'in', 'range' => [self::MESSAGE_TYPE_TEXT, self::MESSAGE_TYPE_IMAGE, self::MESSAGE_TYPE_FILE, self::MESSAGE_TYPE_SYSTEM]],
            [['sender_type'], 'in', 'range' => [self::SENDER_USER, self::SENDER_BOT]],
            [['is_read'], 'boolean'],
            [['is_read'], 'default', 'value' => 0],
            [['chat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Chat::class, 'targetAttribute' => ['chat_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'chat_id' => 'Chat ID',
            'message_type' => 'Message Type',
            'content' => 'Content',
            'result' => 'Result',
            'sender_type' => 'Sender Type',
            'is_read' => 'Is Read',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Chat]].
     *
     * @return ActiveQuery
     */
    public function getChat(): ActiveQuery
    {
        return $this->hasOne(Chat::class, ['id' => 'chat_id']);
    }

    /**
     * Проверить, отправлено ли сообщение пользователем
     */
    public function isFromUser(): bool
    {
        return $this->sender_type === self::SENDER_USER;
    }

    /**
     * Получить форматированное время
     */
    public function getFormattedTime(): string
    {
        return date('H:i', $this->created_at);
    }

    /**
     * Получить форматированную дату
     */
    public function getFormattedDate(): string
    {
        return date('d.m.Y', $this->created_at);
    }

    /**
     * Отметить сообщение как прочитанное
     */
    public function markAsRead(): bool
    {
        $this->is_read = 1;
        return $this->save(false, ['is_read']);
    }
}
