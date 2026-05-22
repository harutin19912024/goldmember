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

// Escape user-supplied / model-returned text before injecting into the DOM.
function escapeHtml(s) {
    return String(s)
        .replace(/&/g, '&amp;').replace(/</g, '&lt;')
        .replace(/>/g, '&gt;').replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

function appendChatLine(role, text, isError) {
    var cls = role === 'user' ? 'user' : (isError ? 'bot error' : 'bot');
    var who = role === 'user' ? 'You' : 'Bot';
    var html = '<div class="' + cls + '"><b>' + who + ':</b> ' + escapeHtml(text).replace(/\\n/g, '<br>') + '</div>';
    var \$m = $('#messages');
    \$m.append(html);
    \$m.scrollTop(\$m[0].scrollHeight);
}

function sendChatMessage() {
    var \$input = $('#chat-input');
    var \$send  = $('#send');
    var message = \$input.val().trim();
    if (!message) return;

    \$input.val('');
    \$send.prop('disabled', true);
    appendChatLine('user', message);
    // Typing indicator
    var \$typing = $('<div class="bot typing"><b>Bot:</b> …</div>').appendTo('#messages');

    $.ajax({
        url: '{$chatUrl}',
        type: 'POST',
        dataType: 'json',
        data: { message: message, _csrf: yii.getCsrfToken() },
        success: function (res) {
            \$typing.remove();
            if (res.success) {
                appendChatLine('bot', res.reply || '(empty reply)');
                if (typeof res.remaining === 'number' && res.remaining <= 5) {
                    appendChatLine('bot', '(' + res.remaining + ' messages left today)', true);
                }
            } else {
                appendChatLine('bot', res.error || 'Error', true);
            }
        },
        error: function (xhr) {
            \$typing.remove();
            var msg = 'Request failed';
            try {
                var j = JSON.parse(xhr.responseText);
                if (j.error) msg = j.error;
            } catch (e) {}
            appendChatLine('bot', msg, true);
        },
        complete: function () {
            \$send.prop('disabled', false);
            \$input.focus();
        }
    });
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

/* Message rows */
#messages .user, #messages .bot { padding: 6px 10px; margin: 4px 0; border-radius: 6px; line-height: 1.4; }
#messages .user { background: #e6f4f3; }
#messages .bot { background: #fff; border: 1px solid #eee; }
#messages .bot.error { background: #fdecec; border-color: #f5c6cb; color: #842029; }
#messages .typing { color: #999; font-style: italic; }
#send:disabled { opacity: 0.6; cursor: not-allowed; }

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


