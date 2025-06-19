<?php

namespace app\modules\dashboard\controllers;

use app\models\Chat;
use app\models\ChatMessage;
use app\models\ChatSearch;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ChatController extends BaseController
{
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'send-message' => ['POST'],
                    'mark-read' => ['POST'],
                ],
            ],
        ]);
    }

    /**
     * Lists all Chat models.
     */
    public function actionIndex(): string
    {
        $searchModel = new ChatSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Chat model.
     */
    public function actionView($id): string
    {
        $model = $this->findModel($id);

        // Отмечаем все сообщения бота как прочитанные
        ChatMessage::updateAll(
            ['is_read' => 1],
            ['chat_id' => $id, 'sender_type' => ChatMessage::SENDER_BOT, 'is_read' => 0]
        );

        // Если это AJAX запрос, возвращаем только контент без лейаута
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('view', [
                'model' => $model,
            ]);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Отправка сообщения через AJAX
     */
    public function actionSendMessage(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Проверяем CSRF токен
        if (!Yii::$app->request->validateCsrfToken()) {
            return ['success' => false, 'message' => 'CSRF токен недействителен'];
        }

        $chatId = Yii::$app->request->post('chat_id');
        $content = Yii::$app->request->post('content');

        if (empty($content)) {
            return ['success' => false, 'message' => 'Сообщение не может быть пустым'];
        }

        $chat = $this->findModel($chatId);

        $message = new ChatMessage([
            'chat_id' => $chatId,
            'message_type' => ChatMessage::MESSAGE_TYPE_TEXT,
            'content' => $content,
            'sender_type' => ChatMessage::SENDER_USER,
            'is_read' => 1,
        ]);

        if ($message->save()) {
            // Обновляем время последнего обновления чата
            $chat->touch('updated_at');

            return [
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'content' => $message->content,
                    'time' => $message->getFormattedTime(),
                    'isOwn' => true,
                ]
            ];
        }

        return ['success' => false, 'message' => 'Ошибка при отправке сообщения', 'errors' => $message->errors];
    }

    /**
     * Получение новых сообщений через AJAX
     */
    public function actionGetMessages($id, $lastMessageId = 0): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $chat = $this->findModel($id);

        $messages = ChatMessage::find()
            ->where(['chat_id' => $id])
            ->andWhere(['>', 'id', $lastMessageId])
            ->orderBy(['created_at' => SORT_ASC])
            ->all();

        $result = [];
        foreach ($messages as $message) {
            $result[] = [
                'id' => $message->id,
                'content' => $message->content,
                'time' => $message->getFormattedTime(),
                'isOwn' => $message->isFromUser(),
            ];
        }

        return ['messages' => $result];
    }

    /**
     * Отметить сообщения как прочитанные
     */
    public function actionMarkRead($id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $updated = ChatMessage::updateAll(
            ['is_read' => 1],
            ['chat_id' => $id, 'sender_type' => ChatMessage::SENDER_BOT, 'is_read' => 0]
        );

        return ['success' => true, 'updated' => $updated];
    }

    /**
     * Finds the Chat model based on its primary key value.
     */
    protected function findModel($id): array|ActiveRecord|null
    {
        if (($model = Chat::find()->where(['id' => $id, 'user_id' => Yii::$app->user->id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}