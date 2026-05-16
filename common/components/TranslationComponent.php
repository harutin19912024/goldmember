<?php

/**
 * Created by PhpStorm.
 * User: Gurgen Gharibyan
 * Date: 8/4/2016
 * Time: 12:08 AM
 */
namespace common\components;


use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use common\models\Language;
class TranslationComponent extends Component
{
    public function getTr($model,$relation, $field)
    {
        if(Yii::$app->language != ""){
            $language = Language::findOne(['short_code'=>Yii::$app->language]);
        }else{
            $language = Language::findOne(['is_default'=>'1']);
        }
        $l_id = $language->id;

        $text='Not Set';
        $translates = $model->$relation;
        foreach($translates as $translate){
            if($translate->lang_id==$l_id) {
                $text =  $translate->$field;
                break;
            }else{
                continue;
            }
        }
        return $text;

    }

}