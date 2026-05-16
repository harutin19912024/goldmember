<?php

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\web\View;
$this->title = Yii::t('app','Mobile-Centre.Shop').' | '.Yii::t('app', 'Blog');
?>
<section id="content">
    <div class="container box blog-page">

        <div class="row blog-header">
            <h1><?=Yii::t('app', 'Blog')?></h1>
        </div>

        <div class="blog-body">
            <!-- Begin blog Result Entries -->
            <?php foreach ($blogs as $key => $blog): ?>
                <div class="blog-result">
                    <h3><a href="/blog/view/<?php echo $blog->id ?>"><?php echo $blog->title ?></a></h3>
                    <span class="blog_date"><?php echo date('d/m/Y', strtotime($blog->created_at)) ?></span>
                    <div class="clearfix"></div>
                    <p><?php echo $blog->short_description ?></p>
                    <a href="" class="btn blog_more pull-right"><?=Yii::t('app', 'Read More')?></a><div class="clearfix"></div>
                </div>
            <?php endforeach; ?>
        </div> 
    </div>
    <!--div class="container pd0">           
        <div class="col-md-12 pd0"> 
            <div class="box pagination">
                <a href="#" class="prev pull-left"><i class="material-icons">&#xE5CB;</i></a>
                <div class="pages">
                    <a href="#" class="active">1</a>
                    <a href="#">2</a>
                    <a href="#" >3</a>
                    <a href="#">4</a>
                    <a href="#">5</a>
                    <a href="#">6</a>
                    <a href="#">7</a>
                    <a href="#">8</a>
                    <a href="#">9</a>
                    <a href="#">10</a>
                </div>
                <a href="#" class="next pull-right"><i class="material-icons">&#xE5CC;</i></a>
            </div>
        </div>
    </div-->
    <div class="container pd0">           
        <div class="col-md-12 pd0"> 
            <?php
            $lastPage = '';
            if (isset(Yii::$app->request->getQueryParams()['page']) && !is_null(Yii::$app->request->getQueryParams()['page'])) {
                $lastPage = (int) (Yii::$app->request->getQueryParams()['page']) - 1;
            }?>
                <div class="box pagination">
                    <a href="<?php echo Url::to('/blog/index/' . $lastPage) ?>" class="prev pull-left"><i class="material-icons">&#xE5CB;</i></a>
                    <div class="pages">
                        <?php if ($last != 1): ?>
                            <?php for ($i = 1; $i <= $last; $i++): ?>
                                <a href="<?php echo Url::to('/blog/index/' . $i) ?>"><?php echo $i ?></a>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </div>
                    <?php
                    $nextPage = '';
                    if (isset(Yii::$app->request->getQueryParams()['page']) && !is_null(Yii::$app->request->getQueryParams()['page'])) {
                        $nextPage = (int) (Yii::$app->request->getQueryParams()['page']) + 1;
                    } if (isset(Yii::$app->request->getQueryParams()['page']) && !is_null(Yii::$app->request->getQueryParams()['page']) && Yii::$app->request->getQueryParams()['page'] != $last):
                        ?>
                        <a href="<?php echo Url::to('/blog/index/' . $nextPage) ?>" class="next pull-right"><i class="material-icons">&#xE5CC;</i></a>
                    <?php endif; ?>
                </div>
        </div>
    </div>
</section>
