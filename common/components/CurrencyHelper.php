<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\components;
use backend\models\Currency;

/**
 * Description of CurrencyHelper
 *
 * @author Harut
 */
class CurrencyHelper {
    
    /**
     * @return string
     */
    public static function changeValue($currency,$how_much)
    {
        $changedCurrency = Currency::find()->where(['id'=>$currency])->one();
        return $how_much * $changedCurrency->exchange_value;
    }

    public static function changeValueWithPrice($amount, $exchange_value){
        return $amount*$exchange_value;
    }
}
