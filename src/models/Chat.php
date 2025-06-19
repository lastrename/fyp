<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "chat".
 *
 * @property int $id
 * @property int $user_id
 * @property string $telegram_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property ChatMessage[] $chatMessages
 * @property User $user
 */
class Chat extends ActiveRecord
{
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
        return 'chat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'telegram_id', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['telegram_id'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'telegram_id' => 'Telegram ID',
            'title' => 'Название чата',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Gets query for [[ChatMessages]].
     *
     * @return ActiveQuery
     */
    public function getChatMessages(): ActiveQuery
    {
        return $this->hasMany(ChatMessage::class, ['chat_id' => 'id'])->orderBy(['chat_message.created_at' => SORT_ASC]);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Получить последнее сообщение чата
     */
    public function getLastMessage(): ActiveQuery
    {
        return $this->hasOne(ChatMessage::class, ['chat_id' => 'id'])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(1);
    }

    /**
     * Получить количество непрочитанных сообщений
     */
    public function getUnreadCount(): int
    {
        return $this->getChatMessages()
            ->where(['is_read' => 0, 'sender_type' => ChatMessage::SENDER_BOT])
            ->count();
    }

    /**
     * Получить отображаемое имя чата
     */
    public function getDisplayName(): string
    {
        return $this->user ? $this->user->username : 'Чат #' . $this->id;
    }

    /**
     * Получить инициалы для аватара
     */
    public function getInitials(): string
    {
        $name = $this->getDisplayName();
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            return mb_strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
        }
        return mb_strtoupper(mb_substr($name, 0, 2));
    }
}
