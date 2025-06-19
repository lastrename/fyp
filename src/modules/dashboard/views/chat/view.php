<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Chat $model */

?>

<!-- Заголовок чата -->
<div class="p-3 border-bottom bg-white">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <div class="position-relative me-3">
                <div class="avatar"><?= Html::encode($model->getInitials()) ?></div>
                <?php if (rand(0, 1)): // Случайный онлайн статус ?>
                    <div class="online-indicator"></div>
                <?php endif; ?>
            </div>
            <div>
                <h6 class="mb-0"><?= Html::encode($model->getDisplayName()) ?></h6>
                <small class="text-success">В сети</small>
            </div>
        </div>
    </div>
</div>

<!-- Сообщения -->
<div class="chat-messages" id="chatMessages">
    <?php foreach ($model->chatMessages as $message): ?>
        <div class="message <?= $message->isFromUser() ? 'own' : '' ?>" data-message-id="<?= $message->id ?>">
            <div class="message-bubble">
                <?= Html::encode($message->content) ?>
            </div>
            <small class="text-muted d-block mt-1"><?= $message->getFormattedTime() ?></small>
        </div>
    <?php endforeach; ?>
</div>

<!-- Поле ввода -->
<div class="chat-input">
    <?= Html::beginForm('', 'post', ['id' => 'messageForm', 'class' => 'mb-0']) ?>
    <?= Html::hiddenInput('chat_id', $model->id) ?>
    <div class="input-group">
        <button class="btn btn-outline-secondary" type="button" title="Прикрепить файл">
            <i class="bi bi-paperclip"></i>
        </button>
        <?= Html::textInput('content', '', [
            'class' => 'form-control',
            'placeholder' => 'Введите сообщение...',
            'id' => 'messageInput',
            'autocomplete' => 'off'
        ]) ?>
        <button class="btn btn-outline-secondary" type="button" title="Эмодзи">
            <i class="bi bi-emoji-smile"></i>
        </button>
        <button class="btn btn-primary" type="submit" id="sendButton">
            <i class="bi bi-send"></i>
        </button>
    </div>
    <?= Html::endForm() ?>
</div>

<?php
$sendMessageUrl = Url::to(['chat/send-message']);
$getMessagesUrl = Url::to(['chat/get-messages', 'id' => $model->id]);

$this->registerJs("
    // Отправка сообщения
    $('#messageForm').on('submit', function(e) {
        e.preventDefault();
        
        const content = $('#messageInput').val().trim();
        if (!content) return;
        
        const chatId = $('input[name=\"chat_id\"]').val();
        
        // Блокируем кнопку отправки
        $('#sendButton').prop('disabled', true);
        
        $.post('$sendMessageUrl', {
            chat_id: chatId,
            content: content
        })
        .done(function(response) {
            if (response.success) {
                // Добавляем сообщение в чат
                const messageHtml = `
                    <div class=\"message own\" data-message-id=\"\${response.message.id}\">
                        <div class=\"message-bubble\">
                            \${response.message.content}
                        </div>
                        <small class=\"text-muted d-block mt-1\">\${response.message.time}</small>
                    </div>
                `;
                
                $('#chatMessages').append(messageHtml);
                $('#messageInput').val('');
                scrollToBottom();
                
                lastMessageId = response.message.id;
            } else {
                alert('Ошибка: ' + response.message);
            }
        })
        .fail(function() {
            alert('Ошибка при отправке сообщения');
        })
        .always(function() {
            $('#sendButton').prop('disabled', false);
            $('#messageInput').focus();
        });
    });
    
    // Отправка по Enter
    $('#messageInput').on('keypress', function(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            $('#messageForm').submit();
        }
    });
    
    // Функция прокрутки вниз
    function scrollToBottom() {
        const chatMessages = $('#chatMessages');
        chatMessages.scrollTop(chatMessages[0].scrollHeight);
    }
    
    // Проверка новых сообщений каждые 3 секунды
    setInterval(function() {
        if (currentChatId) {
            $.get('$getMessagesUrl', { lastMessageId: lastMessageId })
                .done(function(response) {
                    if (response.messages && response.messages.length > 0) {
                        response.messages.forEach(function(message) {
                            const messageClass = message.isOwn ? 'own' : '';
                            const messageHtml = `
                                <div class=\"message \${messageClass}\" data-message-id=\"\${message.id}\">
                                    <div class=\"message-bubble\">
                                        \${message.content}
                                    </div>
                                    <small class=\"text-muted d-block mt-1\">\${message.time}</small>
                                </div>
                            `;
                            
                            $('#chatMessages').append(messageHtml);
                            lastMessageId = message.id;
                        });
                        
                        scrollToBottom();
                    }
                });
        }
    }, 3000);
    
    // Фокус на поле ввода
    $('#messageInput').focus();
");
?>
