<?php

use common\models\Language;

?>


<div>
    <div>
    </div>
</div>
<?php
$languege = Language::find()->where(['short_code' => Yii::$app->language])->asArray()->all();

if ($languege[0]['is_default'] == 1) {
    $answer = trim($model->title);
} else {
    $answer = trim($model->name);
}
?>
<section id="content">
    <div class="container box faq-box">
        <div class="row">
            <div class="faq-head">
                <h2><?= (substr($answer, 0, -1) == '?') ? $answer : $answer . ' ?' ?></h2>
            </div>
            <div class="faq-body">
                <div class="ansver_box">
                    <span><?= $model->description ?></span>
                </div>
            </div>
        </div>
        <div class="row faq-footer">
            <div class="col-md-6">
                <a class="btn btn-big btn-ship pull-left" href="">Back to FAQ list</a>
            </div>
            <div class="col-md-6">
                <div class="answer_help">
                    <span>Is this question help?</span>
                    <div class="ans_feed">
                        <label for="answer_yes">Yes <i class="material-icons">thumb_up</i></label>
                        <input type="radio" id="answer_yes"
                               <?php if ($languege[0]['is_default'] == 1): ?>onclick="addAnswer(<?= $model->id ?>, 1)"
                               <?php else: ?>onclick="addAnswer(<?= $model->faq_id ?>, 1)"<?php endif; ?>
                               name="answer" value="1">
                    </div>
                    <div class="ans_feed">
                        <label for="answer_no">No <i class="material-icons">thumb_down</i></label>
                        <input type="radio" id="answer_no"
                               <?php if ($languege[0]['is_default'] == 1): ?>onclick="addAnswer(<?= $model->id ?>, 0)"
                               <?php else: ?>onclick="addAnswer(<?= $model->faq_id ?>, 0)"<?php endif; ?>
                               name="answer" value="0">
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


