<?php

namespace backend\models;

use Yii;

/**
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
    const LOT_PREFIX = 'GM';

    public static function tableName()
    {
        return 'auction';
    }

    public function rules()
    {
        return [
            [['product_id', 'start_date', 'end_date'], 'required'],
            [['product_id', 'user_id'], 'integer'],
            [['start_date', 'end_date', 'created_date', 'updated_at'], 'safe'],
            [['start_price'], 'number', 'min' => 0],
            [['video_link', 'lot_number'], 'string', 'max' => 255],
            [['lot_number'], 'unique'],
            [['end_date'], 'compare', 'compareAttribute' => 'start_date', 'operator' => '>', 'message' => Yii::t('app', 'End date must be after start date.')],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'video_link' => Yii::t('app', 'Video Link'),
            'start_price' => Yii::t('app', 'Start Price'),
            'user_id' => Yii::t('app', 'User'),
            'lot_number' => Yii::t('app', 'Lot Number'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Auto-generate a unique lot number before insert if the admin didn't supply one.
     * Format: GM-YYYY-NNN where NNN is zero-padded sequence within the current year.
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($insert && empty($this->lot_number)) {
            $this->lot_number = self::nextLotNumber();
        }
        return true;
    }

    /**
     * Generate the next unique lot number in format GM-YYYY-NNN.
     * Counts existing auctions whose lot_number matches this year's prefix; tries with
     * collision retry in case of concurrent inserts.
     */
    public static function nextLotNumber(): string
    {
        $year = date('Y');
        $prefix = self::LOT_PREFIX . '-' . $year . '-';

        // Find the highest existing sequence for this year by parsing the suffix.
        $maxSeq = (int) self::find()
            ->select(new \yii\db\Expression('MAX(CAST(SUBSTRING_INDEX(lot_number, "-", -1) AS UNSIGNED))'))
            ->where(['like', 'lot_number', $prefix . '%', false])
            ->scalar();

        for ($i = 1; $i <= 50; $i++) {
            $candidate = $prefix . str_pad((string)($maxSeq + $i), 3, '0', STR_PAD_LEFT);
            if (!self::find()->where(['lot_number' => $candidate])->exists()) {
                return $candidate;
            }
        }
        // Fallback: timestamp suffix, almost certainly unique.
        return $prefix . substr((string) time(), -5);
    }

    /**
     * Backwards-compat alias used by frontend/views/product/auction-detail.php.
     * If the auction has no lot_number yet, returns a generated one without persisting.
     */
    public static function generateAuctionLotNumber(int $auctionId): string
    {
        $auction = self::findOne($auctionId);
        if ($auction && !empty($auction->lot_number)) {
            return $auction->lot_number;
        }
        return self::nextLotNumber();
    }
}
