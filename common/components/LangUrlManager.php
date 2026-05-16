<?php
/**
 * Created by PhpStorm.
 * User: SAMVEL
 * Date: 8/15/2016
 * Time: 22:54
 */

namespace app\components;
use Yii;

use yii\web\UrlManager;

class LangUrlManager extends UrlManager
{
    public function createUrl($params)
    {
       /* if (empty($params['language']) && Yii::$app->language !== 'en') {
            $params['language'] = Yii::$app->language;
        }
        return parent::createUrl($params);*/
        $url = parent::createUrl($params);
        return MultiLangHelper::addLangToUrl($url);
    }

}