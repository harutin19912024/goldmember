<?php

// Admin-panel gate: guests → login, customers rejected, brokers limited to products.
// Attached here (tracked in git) rather than in main.php (gitignored, per-server)
// so every deployment gets it.
yii\base\Event::on(yii\web\Application::class, yii\web\Application::EVENT_BEFORE_REQUEST, function ($event) {
    $event->sender->attachBehavior('adminAccess', backend\components\AdminAccessControl::class);
});
