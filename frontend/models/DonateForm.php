<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;
use backend\models\DonationInfo;
use Yii;

/**
 * Donate form
 */
class DonateForm extends Model
{
    public $name;
    public $email;
    public $phone;
    public $amount;
    public $message;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 50],
            
            ['phone', 'trim'],
            ['phone', 'required'],
            ['phone', 'string', 'max' => 50],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 50],
            
            ['amount', 'trim'],
            ['amount', 'required'],
            ['amount', 'number', 'min' => 10],

            ['message', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function donate()
    {
        if (!$this->validate()) {
            return false;
        }
        $donate = new DonationInfo();
        $donate->name = $this->name;
        $donate->email = $this->email;
        $donate->phone = $this->phone;
        $donate->amount = $this->amount;
        $donate->message = $this->message;
        if(!$donate->save(false)) {
            return false;
        }
        return true;
    }
}
