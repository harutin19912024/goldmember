
<div class="row">
          <?php foreach($comments as $key =>$value):?>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-1">
                <?= \yii\helpers\Html::img('@web/images/profile.jpg',['style'=>'width:100px;border-radius:50px;margin-top: 15px;'])?>
            </div>
            <div class="col-sm-11">
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading comment_head">
                            <div class="col-sm-6">
                                <div><?=$value->user->customer->name." ".$value->user->customer->surname?></div>
                            </div>
                            <div class="col-sm-6">
                                <div class="pull-right"><?=$value->date?></div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body comment_body" ><div><?=$value->comment ;?></div></div>
                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>
</div>