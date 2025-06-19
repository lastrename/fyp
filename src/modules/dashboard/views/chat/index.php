<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\ChatSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Чаты';

// Регистрируем CSS стили
$this->registerCss('
    .chat-container {
        height: calc(100vh - 100px);
        overflow: hidden;
    }
    
    .chat-sidebar {
        border-right: 1px solid #dee2e6;
        height: 100%;
        overflow-y: auto;
    }
    
    .chat-main {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
        background-color: #f8f9fa;
    }
    
    .chat-input {
        border-top: 1px solid #dee2e6;
        padding: 1rem;
        background-color: white;
    }
    
    .chat-item {
        cursor: pointer;
        transition: background-color 0.2s;
        border: none !important;
    }
    
    .chat-item:hover {
        background-color: #f8f9fa;
    }
    
    .chat-item.active {
        background-color: #e3f2fd;
    }
    
    .message {
        margin-bottom: 1rem;
    }
    
    .message.own {
        text-align: right;
    }
    
    .message-bubble {
        display: inline-block;
        max-width: 70%;
        padding: 0.75rem 1rem;
        border-radius: 1rem;
        word-wrap: break-word;
    }
    
    .message.own .message-bubble {
        background-color: #007bff;
        color: white;
    }
    
    .message:not(.own) .message-bubble {
        background-color: white;
        border: 1px solid #dee2e6;
    }
    
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 14px;
    }
    
    .online-indicator {
        width: 10px;
        height: 10px;
        background-color: #28a745;
        border-radius: 50%;
        position: absolute;
        bottom: 2px;
        right: 2px;
        border: 2px solid white;
    }
    
    .unread-badge {
        background-color: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
    }
');
?>

<div class="chat-index" style="margin: -2rem;">
    <div class="container-fluid p-0">
        <div class="row g-0 chat-container">
            <!-- Левая панель - Список чатов (30%) -->
            <div class="col-12 col-md-4 col-lg-3 chat-sidebar">
                <!-- Заголовок -->
                <div class="p-3 border-bottom">
                    <h5 class="mb-0"><?= Html::encode($this->title) ?></h5>
                </div>

                <!-- Поиск -->
                <div class="p-3">
                    <?= Html::beginForm(['chat/index'], 'get', ['class' => 'mb-0']) ?>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <?= Html::textInput('ChatSearch[search]', $searchModel->search ?? '', [
                            'class' => 'form-control',
                            'placeholder' => 'Поиск чатов...',
                            'id' => 'searchInput'
                        ]) ?>
                    </div>
                    <?= Html::endForm() ?>
                </div>

                <!-- Список чатов -->
                <div class="chat-list">
                    <?php foreach ($dataProvider->models as $index => $chat): ?>
                        <?php
                        $lastMessage = $chat->lastMessage;
                        $unreadCount = $chat->unreadCount;
                        ?>
                        <div class="chat-item p-3 border-bottom <?= $index === 0 ? 'active' : '' ?>"
                             data-chat-id="<?= $chat->id ?>"
                             data-url="<?= Url::to(['chat/view', 'id' => $chat->id]) ?>">
                            <div class="d-flex align-items-center">
                                <div class="position-relative me-3">
                                    <div class="avatar"><?= Html::encode($chat->getInitials()) ?></div>
                                    <?php if (rand(0, 1)): // Случайный онлайн статус для демо ?>
                                        <div class="online-indicator"></div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h6 class="mb-1"><?= Html::encode($chat->getDisplayName()) ?></h6>
                                        <div class="d-flex align-items-center">
                                            <?php if ($lastMessage): ?>
                                                <small class="text-muted me-2"><?= $lastMessage->getFormattedTime() ?></small>
                                            <?php endif; ?>
                                            <?php if ($unreadCount > 0): ?>
                                                <div class="unread-badge"><?= $unreadCount ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-muted small">
                                        <?= $lastMessage ? Html::encode(mb_substr($lastMessage->content, 0, 50) . (mb_strlen($lastMessage->content) > 50 ? '...' : '')) : 'Нет сообщений' ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Правая панель - Чат (70%) -->
            <div class="col-12 col-md-8 col-lg-9 chat-main" id="chatMain">
                <div class="d-flex align-items-center justify-content-center h-100">
                    <div class="text-center text-muted">
                        <i class="bi bi-chat-dots" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Выберите чат для начала общения</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs('
    let currentChatId = null;
    let lastMessageId = 0;
    
    // Обработчик клика по чату
    $(".chat-item").on("click", function() {
        const chatId = $(this).data("chat-id");
        const url = $(this).data("url");
        
        // Убираем активный класс у всех элементов
        $(".chat-item").removeClass("active");
        
        // Добавляем активный класс к выбранному элементу
        $(this).addClass("active");
        
        // Загружаем чат
        loadChat(url, chatId);
    });
    
    // Функция загрузки чата
    function loadChat(url, chatId) {
        currentChatId = chatId;
        
        $.get(url)
            .done(function(data) {
                $("#chatMain").html(data);
                scrollToBottom();
                
                // Получаем ID последнего сообщения
                const messages = $("#chatMessages .message");
                if (messages.length > 0) {
                    lastMessageId = parseInt($(messages.last()).data("message-id")) || 0;
                }
            })
            .fail(function() {
                alert("Ошибка при загрузке чата");
            });
    }
    
    // Функция прокрутки вниз
    function scrollToBottom() {
        const chatMessages = $("#chatMessages");
        if (chatMessages.length) {
            chatMessages.scrollTop(chatMessages[0].scrollHeight);
        }
    }
    
    // Автоматически выбираем первый чат
    if ($(".chat-item").length > 0) {
        $(".chat-item").first().click();
    }
    
    // Поиск с задержкой
    let searchTimeout;
    $("#searchInput").on("input", function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            $("#searchInput").closest("form").submit();
        }, 500);
    });
');
?>
