<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ChatSearch extends Chat
{
    public string $search = '';
    public string $lastMessageContent = '';

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['telegram_id', 'search', 'lastMessageContent'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = Chat::find()
            ->with(['lastMessage', 'user'])
            ->where(['chat.user_id' => \Yii::$app->user->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'updated_at' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Базовые фильтры
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'telegram_id', $this->telegram_id]);

        // Поиск по названию чата, telegram_id и содержимому сообщений
        if (!empty($this->search)) {
            $messageSearchIds = $this->searchInMessages($this->search);

            $searchConditions = ['or',
                ['like', 'title', $this->search],
                ['like', 'telegram_id', $this->search],
            ];

            if (!empty($messageSearchIds)) {
                $searchConditions[] = ['id' => $messageSearchIds];
            }

            $query->andFilterWhere($searchConditions);
        }

        return $dataProvider;
    }

    /**
     * Поиск чатов по содержимому сообщений (отдельный запрос)
     */
    public function searchInMessages($searchTerm): array
    {
        if (empty($searchTerm)) {
            return [];
        }

        return ChatMessage::find()
            ->select('DISTINCT chat_id')
            ->joinWith('chat')
            ->where(['chat.user_id' => \Yii::$app->user->id])
            ->andWhere(['like', 'content', $searchTerm])
            ->column();
    }
}