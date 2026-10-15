<?php

namespace backend\models;

use Yii;
use yii\db\Query;
use common\models\User;

/**
 * Numbers for the admin home page. Each metric is independent and fails soft
 * (null) so one missing table/column never breaks the dashboard.
 */
class DashboardStats
{
    public static function collect(): array
    {
        $now = date('Y-m-d H:i:s');
        $today = date('Y-m-d');
        $monthStart = date('Y-m-01 00:00:00');
        $weekAgo = date('Y-m-d H:i:s', strtotime('-7 days'));

        $s = [];
        $s['visitorsToday'] = self::scalar(fn() => (new Query())->from('user_activity')
            ->where(['>=', 'last_activity', $today . ' 00:00:00'])->count('DISTINCT session_id'));
        $s['visitorsWeek'] = self::scalar(fn() => (new Query())->from('user_activity')
            ->where(['>=', 'last_activity', $weekAgo])->count('DISTINCT session_id'));
        $s['newCustomersWeek'] = self::scalar(fn() => (new Query())->from('user')
            ->where(['role' => User::CUSTOMER])->andWhere(['>=', 'created', $weekAgo])->count());
        $s['customersTotal'] = self::scalar(fn() => (new Query())->from('user')->where(['role' => User::CUSTOMER])->count());

        $open = ['closed_at' => null];
        $s['auctionsLive'] = self::scalar(fn() => (new Query())->from('auction')->where($open)
            ->andWhere(['<=', 'start_date', $now])->andWhere(['>', 'end_date', $now])->count());
        $s['auctionsUpcoming'] = self::scalar(fn() => (new Query())->from('auction')->where($open)
            ->andWhere(['>', 'start_date', $now])->count());
        $s['bidsToday'] = self::scalar(fn() => (new Query())->from('bid')->where(['>=', 'created_at', $today . ' 00:00:00'])->count());
        $s['soldThisMonth'] = self::scalar(fn() => (new Query())->from('auction')
            ->where(['>=', 'closed_at', $monthStart])->andWhere(['not', ['winner_user_id' => null]])->count());
        $s['soldValueThisMonth'] = self::scalar(fn() => (new Query())->from('auction')
            ->where(['>=', 'closed_at', $monthStart])->sum('winning_amount'));

        $s['donationsThisMonth'] = self::scalar(fn() => (new Query())->from('donation_info')->where(['>=', 'created_at', $monthStart])->count());
        $s['donationsSumThisMonth'] = self::scalar(fn() => (new Query())->from('donation_info')->where(['>=', 'created_at', $monthStart])->sum('amount'));

        // Latest gold (XAU) spot row from the API fetch.
        $s['gold'] = null;
        try {
            $row = (new Query())->from('metal_price_real')->where(['metal_id' => 1])->orderBy(['id' => SORT_DESC])->one();
            if ($row) {
                $d = $row['request_data'];
                for ($i = 0; $i < 2 && is_string($d); $i++) {
                    $d = json_decode($d, true);
                }
                if (is_array($d) && !empty($d['price'])) {
                    $s['gold'] = [
                        'perGram'   => $d['price'] / 31.1035,
                        'changePct' => !empty($d['prev_close_price']) ? ($d['price'] - $d['prev_close_price']) / $d['prev_close_price'] * 100 : null,
                        'fetchedAt' => $row['created_date'],
                    ];
                }
            }
        } catch (\Throwable $e) {
            Yii::warning('Dashboard gold price: ' . $e->getMessage(), __METHOD__);
        }
        return $s;
    }

    private static function scalar(callable $fn)
    {
        try {
            return $fn();
        } catch (\Throwable $e) {
            Yii::warning('Dashboard metric failed: ' . $e->getMessage(), __METHOD__);
            return null;
        }
    }
}
