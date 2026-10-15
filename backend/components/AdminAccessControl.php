<?php

namespace backend\components;

use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use common\models\User;

/**
 * App-wide gate for the admin panel, attached in backend/config/main.php as
 * 'as access'. Runs before every controller's own behaviors:
 *  - guests may only reach site/login and site/error
 *  - admins (role 0) may reach everything
 *  - brokers (role 1) may reach only the product section
 *  - anyone else (customers) is logged out and sent back to the login page
 * Per-controller AccessControl rules still apply on top of this.
 */
class AdminAccessControl extends AccessControl
{
    /** Controllers a broker may use (product management + its AJAX helpers). */
    const BROKER_CONTROLLERS = ['site', 'product', 'product-attribute', 'tr-product', 'attribute'];

    public function init()
    {
        $this->rules = [
            [
                'allow' => true,
                'controllers' => ['site'],
                'actions' => ['login', 'error'],
            ],
            [
                'allow' => true,
                'roles' => ['@'],
                'matchCallback' => function () {
                    return self::currentRole() === User::ADMIN;
                },
            ],
            [
                'allow' => true,
                'roles' => ['@'],
                'controllers' => self::BROKER_CONTROLLERS,
                'matchCallback' => function () {
                    return self::currentRole() === User::BROKER;
                },
            ],
        ];
        $this->denyCallback = function ($rule, $action) {
            $user = Yii::$app->user;
            if ($user->getIsGuest()) {
                // Background requests (debug toolbar, AJAX widgets on the login page)
                // must get a plain 403: redirecting them sends X-Redirect, which
                // yii.js follows by reloading the page — an endless reload loop.
                if (Yii::$app->request->getIsAjax()) {
                    throw new ForbiddenHttpException('Login required.');
                }
                return $user->loginRequired();
            }
            if (!in_array(self::currentRole(), User::BACKEND_ROLES, true)) {
                // A customer session (e.g. a remember-me cookie from before this
                // check existed) has no business here at all.
                $user->logout();
                return $user->loginRequired();
            }
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        };
        parent::init();
    }

    private static function currentRole()
    {
        $identity = Yii::$app->user->identity;
        return $identity ? (int)$identity->role : null;
    }
}
