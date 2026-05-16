<?php

namespace frontend\models;

use Yii;

/**
 * Product purchase orders table `product_orders`.
 *
 * @property int    $id
 * @property int    $user_id
 * @property int    $product_id
 * @property int    $quantity
 * @property float  $price_amd
 * @property int    $status   0=pending, 1=confirmed, 2=delivered, 3=cancelled
 * @property string $note
 * @property string $created_at
 */
class ProductOrder extends \yii\db\ActiveRecord
{
    const STATUS_PENDING   = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_DELIVERED = 2;
    const STATUS_CANCELLED = 3;

    public static $statusLabels = [
        self::STATUS_PENDING   => 'Pending',
        self::STATUS_CONFIRMED => 'Confirmed',
        self::STATUS_DELIVERED => 'Delivered',
        self::STATUS_CANCELLED => 'Cancelled',
    ];

    public static function tableName()
    {
        return 'product_orders';
    }

    public function rules()
    {
        return [
            [['user_id', 'product_id', 'price_amd'], 'required'],
            [['user_id', 'product_id', 'quantity', 'status'], 'integer'],
            [['price_amd'], 'number'],
            [['note'], 'string', 'max' => 500],
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(\backend\models\Product::class, ['id' => 'product_id']);
    }

    public static function getStatusLabel(int $status): string
    {
        return self::$statusLabels[$status] ?? 'Unknown';
    }

    public static function getStatusBadgeStyle(int $status): string
    {
        return match ($status) {
            self::STATUS_DELIVERED => 'background:#d1f5e0;color:#1a7a3a;',
            self::STATUS_CANCELLED => 'background:#fde8e8;color:#a01414;',
            self::STATUS_CONFIRMED => 'background:#d1e8fd;color:#0a4d8c;',
            default                => 'background:#f5f0d1;color:#8c6a0a;',
        };
    }
}
