<?php

namespace frontend\models;

use Yii;
use backend\models\TrPages;
/**
 * This is the model class for table "pages".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $status
 * @property integer $ordering
 * @property string $created_date
 * @property string $updated_date
 *
 * @property TrPages[] $trPages
 */
class Pages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages';
    }

    public static function findList($filters = []){
        $language = Yii::$app->language;
		$where = ['language.short_code' => $language];
		if (!empty($filters) && isset($filters['type'])) {
            $where = array_merge($where, ['pages.type' => $filters['type']]);
        }
		if (!empty($filters) && isset($filters['position'])) {
            $where = array_merge($where, ['pages.position' => $filters['position']]);
        }
        $rows = (new \yii\db\Query())
            ->select(['pages.id', 'tr_pages.title','pages.route_name'])
            ->from('pages')
            ->leftJoin('tr_pages','pages.id = tr_pages.pages_id')
            ->leftJoin('language','language.id = tr_pages.language_id')
			->where($where)
            ->orderBy(['pages.ordering'=>SORT_ASC])
            ->all();

        return $rows;
    }



}
