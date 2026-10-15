<?php

namespace common\components;

use yii\swiftmailer\Mailer as SwiftMailer;

/**
 * Swiftmailer 6 on PHP 8.2 raises E_DEPRECATED ("Callables of the form
 * [Swift_Message, ...] are deprecated") while building messages. With
 * YII_DEBUG on, Yii turns that into an exception, so every email failed.
 * This wrapper mutes only deprecations while Swift code runs.
 *
 * It replaces yii\swiftmailer\Mailer through the DI container (see
 * common/config/bootstrap.php), so per-server `mailer` configs keep working.
 *
 * SMTP is read from the environment when SMTP_HOST is set, e.g. in .env:
 *   SMTP_HOST, SMTP_PORT (587), SMTP_USER, SMTP_PASS, SMTP_ENCRYPTION (tls)
 * Without SMTP_HOST the server's own `mailer` config applies unchanged.
 *
 * Long-term fix: migrate to yiisoft/yii2-symfonymailer (needs composer).
 */
class Mailer extends SwiftMailer
{
    public function init()
    {
        parent::init();
        $host = getenv('SMTP_HOST');
        if ($host) {
            $this->useFileTransport = false;
            $this->setTransport([
                'class'      => 'Swift_SmtpTransport',
                'host'       => $host,
                'port'       => (int)(getenv('SMTP_PORT') ?: 587),
                'username'   => getenv('SMTP_USER') ?: null,
                'password'   => getenv('SMTP_PASS') ?: null,
                'encryption' => getenv('SMTP_ENCRYPTION') ?: 'tls',
            ]);
        }
    }

    public function compose($view = null, array $params = [])
    {
        return $this->withoutDeprecations(function () use ($view, $params) {
            return parent::compose($view, $params);
        });
    }

    public function send($message)
    {
        return $this->withoutDeprecations(function () use ($message) {
            return parent::send($message);
        });
    }

    /** True when mail actually leaves the server (not written to runtime/mail). */
    public function isConfigured()
    {
        return !$this->useFileTransport;
    }

    private function withoutDeprecations(callable $fn)
    {
        $level = error_reporting(error_reporting() & ~E_DEPRECATED & ~E_USER_DEPRECATED);
        try {
            return $fn();
        } finally {
            error_reporting($level);
        }
    }
}
