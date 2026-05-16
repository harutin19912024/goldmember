<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\models\Pages;
use backend\models\Files;
$this->title = Yii::t('app','SANSTROY').' | '.Yii::t('app', $page['title']);
$this->params['breadcrumbs'][] = $this->title;
$subpages = Pages::find()->where(['parent_id'=>$page['id']])->asArray()->all();
$images = Files::find()->where(['category' => 'pages', 'category_id' => $page['id']])->asArray()->all();
?>
<div class="container">
                <div class="row wrapper-main">
                    <?= $this->render('/site/category',['choosen_category'=>0]) ?>
					<div class="col-lg-9 col-sm-8">
      <div class="row">
        <div class="col-xs-12 page-title text-center">
          <h1><?= Html::encode(Yii::t('app', Yii::t('app', $page['title']))) ?></h1>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <div class="cleancode page-content">
		  <?=$page['content'] ?>
            </div>
        </div>
      </div>
	  
	  
	  <div id="accordion">
	  <?php if(!empty($subpages)):?>
	  <?php foreach($subpages as $pages):?>
  <div class="card">
    <div class="card-header" id="heading_<?=$pages['id']?>">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse_<?=$pages['id']?>" aria-expanded="false" aria-controls="collapse_<?=$pages['id']?>">
          <?=$pages['title']?>
        </button>
      </h5>
    </div>
    <div id="collapse_<?=$pages['id']?>" class="collapse" aria-labelledby="heading_<?=$pages['id']?>" data-parent="#accordion">
      <div class="card-body"><?=$pages['content']?></div>
    </div>
  </div>
  <?php endforeach;?>
  <?php endif;?>
</div>

<?php if(!empty($images)):?>
<div class="works">
				<ul class="grid effect-3" id="grid">
					<?php foreach($images as $image):?>
				<li class="cols col-xs-12 col-sm-6 col-md-4 col-lg-4 animate" data-responsive="" data-src="<?= Yii::$app->params['adminUrl'].'uploads/images/pages/'.$page['id'].'/' . $image['path'] ?>" data-sub-html="">
							<div class="post-hentry-works">
						<a href="javascript:;" target="_blank">
						<?php
                                                            echo Html::img(Yii::$app->params['adminUrl'].'uploads/images/pages/'.$page['id'].'/' . $image['path'], [
                                                                'class' => 'img-responsive article-image',
                                                                'alt' => '',
                                                            ])
                                                            ?>
								</a>
															
							</div>
						</li>
						<?php endforeach;?>
				</ul>
			</div>
			<?php endif;?>
    </div>
    </div>
    </div>
	
	<?php
$this->registerJs("   	
    $(document).ready(function(){
	      $('#grid').lightGallery();
	    });
")?>
