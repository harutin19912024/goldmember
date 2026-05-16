<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;
use yz\shoppingcart\CartPositionInterface;
use yz\shoppingcart\CartPositionTrait;

/**
 * This is the model class for table "service".
 *
 * @property integer $id
 * @property integer $price
 * @property string $name
 * @property string $description
 * @property string $short_description
 * @property integer $status
 * @property string $created_date
 * @property string $updated_date
 *
 * @property Repairs[] $repairs
 */
class Service extends \yii\db\ActiveRecord implements CartPositionInterface
{
    use CartPositionTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description',], 'required'],
            [['description'], 'string'],
            [['status'], 'integer'],
            [['price'], 'number'],
            [['created_date', 'updated_date'], 'safe'],
            [['name', 'short_description'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'short_description' => Yii::t('app', 'Short Description'),
            'status' => Yii::t('app', 'Status'),
            'price' => Yii::t('app', 'Price'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRepairs()
    {
        return $this->hasMany(Repairs::className(), ['service_id' => 'id']);
    }

    public static function getActiveServices()
    {
        return self::find()->where(['status' => 1])->asArray()->all();
    }

    public function getServicesByProduct($product_id)
    {
        $repairs = Repairs::find()->where(['product_id' => $product_id])->asArray()->all();
        $services_id = array_values(ArrayHelper::map($repairs, 'id', 'service_id'));
        $data = self::findAll($services_id);
        return ArrayHelper::toArray($data);
    }

    public static function findByItemIdandServiceName($item_id, $service_name, $type)
    {
        if ($type == 0) {
            $service_id = Service::findBySql("SELECT `service`.`id` from `service` LEFT JOIN `repairs` ON ".
                                            "`service`.id = `repairs`.`service_id` ".
                                           " WHERE `repairs`.`part_id` = $item_id ".
                                            "AND  `service`.`name` = '".$service_name."'")
                ->asArray()->all();
        } else {
            $service_id = Service::findBySql("SELECT `service`.`id` from `service` LEFT JOIN `repairs` ON ".
                                           " `service`.id = `repairs`.`service_id`".
                                           " WHERE `repairs`.`product_id` = $item_id".
                                           " AND  `service`.`name` = '".$service_name."'")
                ->asArray()->all();
        }
        return $service_id;
    }

    /**
     * @param $service_id
     * @return bool
     */
    public static function UpadatePartCount($service_id)
    {
        $repair = Repairs::findOne(['service_id' => $service_id]);
        if ($repair->part_id > 0) {
            $part = ProductParts::findOne($repair->part_id);
            $part->in_stock -= 1;
            if ($part->update()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }
}
