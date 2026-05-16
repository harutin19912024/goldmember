<?php

namespace common\components;

use Yii;

class MultiLangHelper {

    public static function enabled() {
        return count(Yii::$app->params['translatedLanguages']) > 1;
    }

    public static function suffixList() {
        $list = array();
        $enabled = self::enabled();

        foreach (Yii::$app->params['translatedLanguages'] as $lang => $name) {
            if (\Yii::$app->language != $lang) {
                $suffix = '_' . $lang;
                $list[$suffix] = $name;
            }
        }

        return $list;
    }

    public static function processLangInUrl($url) {
        $domains = explode('/', ltrim($url, '/'));

        if (self::enabled() && explode('/', ltrim($url, '/'))[0] != 'admin') {
            $domains = explode('/', ltrim($url, '/'));

            $isLangExists = in_array($domains[0], array_keys(Yii::$app->params['translatedLanguages']));
            $isDefaultLang = $domains[0] == Yii::$app->params['defaultLanguage'];

            if ($isLangExists && !$isDefaultLang) {

                $lang = array_shift($domains);

                Yii::$app->language = $lang;
            } else {
                Yii::$app->language = Yii::$app->params['defaultLanguage'];
            }


            $url = '/' . implode('/', $domains);
        }

        return $url;
    }

    public static function addLangToUrl($url) {
        if (self::enabled() && explode('/', ltrim($url, '/'))[0] != 'admin') {
            $domains = explode('/', ltrim($url, '/'));
            $isHasLang = in_array($domains[0], array_keys(Yii::$app->params['translatedLanguages']));
            $isDefaultLang = Yii::$app->language == Yii::$app->params['defaultLanguage'];

            if ($isHasLang && $isDefaultLang)
                array_shift($domains);

            if (!$isHasLang && !$isDefaultLang)
                array_unshift($domains, Yii::$app->language);

            $url = '/' . implode('/', $domains);
        }

        return $url;
    }

}
