<?php
use yii\helpers\Url;
use yii\web\View;

/** @var View $this */

$chatUrl = Url::to(['chat/ask']);

$js = <<<JS
function toggleChat(open) {
    const wrapper = $('#chat-wrapper');
    const toggleBtn = $('#chat-toggle');

    if (open) {
        wrapper.removeClass('chat-closed');
        toggleBtn.text('−');
    } else {
        wrapper.addClass('chat-closed');
        toggleBtn.text('+');
    }
}

// Default closed
toggleChat(false);

// Toggle on header or button click
$('#chat-header, #chat-toggle').on('click', function () {
    const isClosed = $('#chat-wrapper').hasClass('chat-closed');
    toggleChat(isClosed);
});

// Send message
function sendChatMessage() {
    const message = $('#chat-input').val().trim();
    if (!message) return;

    $.ajax({
        url: '{$chatUrl}',
        type: 'POST',
        dataType: 'json',
        data: {
            message: message,
            _csrf: yii.getCsrfToken()
        },
        success: function (res) {
            if (res.success) {
                $('#messages').append(
                    '<div class="user"><b>You:</b> ' + message + '</div>' +
                    '<div class="bot"><b>Bot:</b> ' + res.reply + '</div>'
                );

                // Auto-scroll to bottom
                $('#messages').scrollTop($('#messages')[0].scrollHeight);
            } else {
                alert(res.error || 'Error');
            }
        },
        error: function () {
            alert('Request failed');
        }
    });

    $('#messages').append(
        '<div class="user"><b>You:</b> ' + message + '</div>'
    );

    $('#chat-input').val('');
    $('#messages').scrollTop($('#messages')[0].scrollHeight);
}


// Button click
$('#send').on('click', sendChatMessage);

// Enter key
$('#chat-input').on('keydown', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        sendChatMessage();
    }
});

JS;

$this->registerJs($js);

?>
<style>
/* Wrapper */
#chat-wrapper {
    position: fixed;
    right: 20px;
    bottom: 20px;
    width: 320px;
    font-family: Arial, sans-serif;
    z-index: 9999;
}

/* Header */
#chat-header {
    background: #13B2AD;
    color: #fff;
    padding: 10px 12px;
    border-radius: 12px 12px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}

/* Toggle button */
#chat-toggle {
    background: none;
    border: none;
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    line-height: 1;
}

/* Chat body */
#chat {
    background: #fff;
    height: 400px;
    display: flex;
    flex-direction: column;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border-radius: 0 0 12px 12px;
    overflow: hidden;
}

/* Messages */
#messages {
    flex: 1;
    padding: 12px;
    overflow-y: auto;
    background: #f7f7f7;
    font-size: 14px;
}

/* Input row */
.input-row {
    display: flex;
    border-top: 1px solid #ddd;
}

#chat-input {
    flex: 1;
    border: none;
    padding: 10px;
    outline: none;
}

/* Closed state */
.chat-closed #chat {
    display: none;
}

.chat-closed #chat-toggle {
    content: '+';
}

</style>


<div id="chat-wrapper" class="chat-closed">
    <div id="chat-header">
        <span><?=Yii::t('app', 'Ask me')?></span>
        <button id="chat-toggle" type="button">+</button>
    </div>

    <div id="chat">
        <div id="messages"></div>

        <div class="input-row">
            <input type="text" id="chat-input" placeholder="Ask something..." />
            <button id="send" class="btn bg-primary-color white-color"><?=Yii::t('app', 'Send')?></button>
        </div>
    </div>
</div>


