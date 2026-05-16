<?php

namespace common\components;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Application;
use yii\db\Expression;

/**
 * Tracks every frontend request in user_activity.
 * Registered as a bootstrap component in frontend/config/main.php.
 */
class VisitorTracker implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $app->on(Application::EVENT_BEFORE_ACTION, function () {
            try {
                $db        = Yii::$app->db;
                $sessionId = Yii::$app->session->id;
                $ip        = Yii::$app->request->userIP ?? '0.0.0.0';
                $userId    = Yii::$app->user->isGuest ? null : Yii::$app->user->id;

                // Upsert: update last_activity if the session already exists,
                // insert a new row otherwise.
                $exists = $db->createCommand(
                    'SELECT id FROM user_activity WHERE session_id = :sid LIMIT 1',
                    [':sid' => $sessionId]
                )->queryScalar();

                if ($exists) {
                    $db->createCommand(
                        'UPDATE user_activity
                            SET last_activity = NOW(),
                                user_id       = :uid,
                                ip_address    = :ip
                          WHERE session_id    = :sid',
                        [':uid' => $userId, ':ip' => $ip, ':sid' => $sessionId]
                    )->execute();
                } else {
                    $db->createCommand(
                        'INSERT INTO user_activity (user_id, session_id, ip_address, last_activity, created_at)
                              VALUES (:uid, :sid, :ip, NOW(), NOW())',
                        [':uid' => $userId, ':sid' => $sessionId, ':ip' => $ip]
                    )->execute();
                }
            } catch (\Throwable $e) {
                // Never let tracking crash the page
                Yii::warning('VisitorTracker error: ' . $e->getMessage(), 'visitor');
            }
        });
    }
}
