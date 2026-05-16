<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "customer_card".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $card_nuber
 * @property string $date_from
 * @property string $date_to
 * @property integer $cv_code
 *
 * @property Customer $customer
 */
class CustomerCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_to', 'card_nuber', 'cv_code'], 'required'],
            [['customer_id', 'cv_code'], 'integer'],
            [['card_nuber'], 'match', 'pattern' => '/^(5[1-5][0-9]{14}|4[0-9]{12}([0-9]{3})?|3[47][0-9]{13}|3(0[0-5]|[68][0-9])[0-9]{11}|(6011\d{12}|65\d{14})|(3[0-9]{4}|2131|1800)[0-9]{11}|2(?:014|149)\\d{11}|8699[0-9]{11}|(6334[5-9][0-9]|6767[0-9]{2})\\d{10}(\\d{2,3})?|(?:5020|6\\d{3})\\d{12}|56(10\\d\\d|022[1-5])\\d{10}|(?:49(03(0[2-9]|3[5-9])|11(0[1-2]|7[4-9]|8[1-2])|36[0-9]{2})\\d{10}(\\d{2,3})?)|(?:564182\\d{10}(\\d{2,3})?)|(6(3(33[0-4][0-9])|759[0-9]{2})\\d{10}(\\d{2,3})?)|(?:417500|4026\\d{2}|4917\\d{2}|4913\\d{2}|4508\\d{2}|4844\\d{2})\\d{10}|(?:417500|4026\\d{2}|4917\\d{2}|4913\\d{2}|4508\\d{2}|4844\\d{2})\\d{10})$/'],
            [['date_from', 'date_to'], 'match', 'pattern' => '/^[0-9]{2}[\/]{1}[0-9]{4}$/',],
            [ 'date_to', 'validateExpiredDate'],
            [ 'date_from', 'validateFromDate'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'card_nuber' => Yii::t('app', 'Card Number'),
            'date_from' => Yii::t('app', 'Date From'),
            'date_to' => Yii::t('app', 'Date To'),
            'cv_code' => Yii::t('app', 'Cv Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     *
     * Validates a Credit Card date
     *
     * @param integer $creditCardExpiredMonth
     * @param integer $creditCardExpiredYear
     *
     * @return bool
     */
    public function validateExpiredDate($attribute)
    {
        $date = explode('/', $this->$attribute);
        $creditCardExpiredMonth =$date[0];
        $creditCardExpiredYear = $date[1];
        $currentYear = intval(date('Y'));
        $currentMonth = intval(date('m'));
        if (is_scalar($creditCardExpiredMonth)) $creditCardExpiredMonth = intval($creditCardExpiredMonth);
        if (is_scalar($creditCardExpiredYear)) $creditCardExpiredYear = intval($creditCardExpiredYear);
        $isValid = is_integer($creditCardExpiredMonth) && is_integer($creditCardExpiredYear) && $creditCardExpiredMonth <= 12
            && ($creditCardExpiredMonth >= 1 && $creditCardExpiredYear > $currentYear
                && $creditCardExpiredYear < $currentYear + 21) || ($creditCardExpiredYear == $currentYear && $creditCardExpiredMonth >= $currentMonth);
        if(!$isValid){
            $this->addError('date_to','Invalid date!');
            return false;
        }
        return true;
    }

    public function validateFromDate($attribute)
    {
        $date = explode('/', $this->$attribute);
        $creditCardFromMonth =$date[0];
        $creditCardFromYear = $date[1];
        $currentYear = intval(date('Y'));

        if (is_scalar($creditCardFromMonth)) $creditCardFromMonth = intval($creditCardFromMonth);
        if (is_scalar($creditCardFromYear)) $creditCardFromYear = intval($creditCardFromYear);
        $isValid = is_integer($creditCardFromMonth) && is_integer($creditCardFromYear) && $creditCardFromMonth <= 12
            && ($creditCardFromMonth >= 1 && $creditCardFromYear <= $currentYear
                && $creditCardFromYear >= $currentYear - 10);
        if(!$isValid){
            $this->addError('date_from','Invalid date!');
            return false;
        }
        return true;
    }
}
