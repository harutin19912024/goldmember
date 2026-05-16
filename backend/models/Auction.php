<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "auction".
 *
 * @property int $id
 * @property int $product_id
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $video_link
 * @property float|null $start_price
 * @property int|null $user_id
 * @property string|null $lot_number
 * @property string $created_date
 * @property string|null $updated_at
 *
 * @property Product $product
 * @property User $user
 */
class Auction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id'], 'required'],
            [['product_id', 'user_id'], 'integer'],
            [['start_date', 'end_date', 'created_date', 'updated_at'], 'safe'],
            [['start_price'], 'number'],
            [['video_link', 'lot_number'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'video_link' => Yii::t('app', 'Video Link'),
            'start_price' => Yii::t('app', 'Start Price'),
            'user_id' => Yii::t('app', 'User ID'),
            'lot_number' => Yii::t('app', 'Lot Number'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
    
    /**
     * Generates a formatted Auction Lot Number like: "Lot N: 2435/4"
     *
     * @param int $auctionId The ID of the auction
     * @return string The formatted lot number
     */
    public static function generateAuctionLotNumber(int $auctionId): string
    {
        $lotCount = self::find()
            ->where(['id' => $auctionId])
            ->count();
    
        $lastGlobalId = self::find()->max('id');
        $nextGlobalId = $lastGlobalId ? $lastGlobalId + 1 : 1;
    
        return "Lot N: {$nextGlobalId}/{$auctionId}";
    }
}
