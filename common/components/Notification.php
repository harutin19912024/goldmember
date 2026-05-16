<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 08.09.2016
 * Time: 18:14
 */

namespace common\components;

use Yii;

class Notification
{
    public static function sendPushNotification($DeviceTokens, $Message, $Params = [])
    {
        $apns = Yii::$app->apns;
        $apns->sendMulti($DeviceTokens, $Message, $Params,
            [
                'sound' => 'default',
                'badge' => 1
            ]
        );
        if($apns->success){
            return true;
        }else{
           return $apns->errors;
        }

    }
}