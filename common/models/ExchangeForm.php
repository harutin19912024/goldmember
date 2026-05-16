<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class ExchangeForm extends Model {

    public $metal;
    public $currency;
    public $startDate;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['metal', 'currency', 'startDate'], 'required'],
            [['metal', 'currency', 'startDate', 'data'], 'safe']
        ];
    }
}
