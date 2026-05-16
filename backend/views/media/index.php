<?php

use backend\models\Marks;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\MarksSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', $pageName);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marks-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create?isGallery='.$isGallery], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
           ['attribute' => 'path',
                                'format' => 'html',
                                'label' => '',
								'headerOptions' => ['style' => 'width: 5%;'],
                                'value' => function ($model) {
                                    $image = $model->getDefaultImage($model->id);

                                    if (isset($image[1])) {
                                        $path = 'uploads/images/' . $image[1];
                                    } else {
                                        $path = 'images/goldmember.jpg';
                                    }

                                    return Html::img('/' . $path, ['style' => 'width: 40px !important']);
                                },
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'Search'
                                ],
                            ],
            'title',
            ['class' => 'yii\grid\ActionColumn',
                                'template' => '{edit}{view}{delete}',
                                'buttons' => [
                                    'edit' => function ($url, $model) {
                                        $title = '';
                                        $btn_class = "";
                                        $data_pjax = '';
                                        if ($model['status'] == 0) {
                                            $data_pjax = json_encode(['id' => $model['id'], 'status' => 1]);
                                            $title = Yii::t('app',"Enable <i class=\"fa fa-toggle-on text-success\"></i>");
                                            $btn_class = "btn-success";
                                            $cust_status = false;

                                        } elseif ($model['status'] == 1) {
                                            $data_pjax = json_encode(['id' => $model['id'], 'status' => 0]);
                                            $title = Yii::t('app',"Disable <i class=\"fa fa-toggle-off\"></i>");
                                            $btn_class = "btn-danger";
                                            $cust_status = true;
                                        }
                                        $link = '<div class="chsts switch switch-xs switch-primary round switch-inline">'.
                                            Html::checkbox($title,$cust_status,
                                            ['title' => Yii::t('app', $title),
                                                'class' => 'btn ' . $btn_class . ' br2 btn-xs fs12 cust_change_status',
                                                'id'=>'chk_sts'.$model->id,
                                                'data-pjax' => $data_pjax
                                            ]).'<label for="chk_sts'.$model->id.'" ></label></div>';
                                        return $link;
                                    },
                                    'view'=>function($url,$model){
                                        return Html::a(
                                            '<div class="btn-group">
                                                  <button type="button" class="btn btn-xs btn-info">
                                                    <i class="fa fa-eye"></i>
                                                  </button>
                                                </div>',
                                            $url,
                                            [
                                                'title' => Yii::t('app','View'),
                                                'aria-label' => Yii::t('app','View'),
                                                'data-pjax' => '0',
//                                                'class' =>'btn btn-info btn-xs fs12 br2 ml5',
                                            ]);
                                    },
                                    'delete' => function ($url, $model) use($isGallery) {

                                        return Html::a('<span class="glyphicon glyphicon-trash"></span> ',
                                            $url.'&isGallery='.$isGallery,
                                            [
                                                'title' => 'Delete',
                                                'aria-label' => 'Delete',
                                                'data-confirm' => 'Are you sure! You whant delete this item?',
                                                'data-method' => 'post',
                                                'data-pjax' => '0',
                                                'data-key' => $model->id,
                                                'class' => 'btn btn-danger btn-xs fs12 br2 ml5'
                                            ]);
                                    },
                                ],
                                ],
        ],
    ]); ?>


</div>
