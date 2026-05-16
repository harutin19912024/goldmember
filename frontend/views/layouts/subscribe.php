<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="newsletter">
                <h2><?= Yii::t('app','Subscribe To Our Newsletters');?></h2>
                <span><?= Yii::t('app','Subscribe to our newsletter to receive news & ubdates.<br> We Promise to not spam you super promise!');?></span>
                <form class="form-inline">
                    <div class="form-group col-md-8">
                        <input type="email" class="form-control" id="email" placeholder="<?= Yii::t('app','INPUT YOUR EMAIL');?>">
                    </div>
                    <button type="submit" class="btn btn-default"><?= Yii::t('app','SUBSCRIBE');?></button>
                </form>
            </div>
        </div>
    </div>
</div>