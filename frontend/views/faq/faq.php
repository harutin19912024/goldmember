<?php
use yii\helpers\Url;

?>

<section id="content">
    <div class="container box faq-box">
        <div class="row">
            <div class="faq-head">
                <h2>F.A.Q.</h2>
            </div>
            <div class="faq-body">
                <div class="col-md-6">
                    <div class="panel-group accordion accordion-lg" id="accordion">
                    <?php foreach ($faq as $value): ?>
                            <div class="panel">
                                <div class="panel-heading">
                                    <a class="accordion-toggle accordion-icon link-unstyled" data-toggle="collapse" data-parent="#accordion<?php echo $value->id ?>" href="#accord<?php echo $value->id ?>" aria-expanded="true">
                                        <i
                                            class="material-icons">help_outline</i> <?php if ($isDefaultLan): ?><?php echo $value->title ?><?php else: ?><?= $value->name ?><?php endif; ?>
                                    </a>
                                </div>
                                <div id="accord<?php echo $value->id ?>" class="panel-collapse faq-answer collapse" aria-expanded="true">
                                    <?= $value->description ?>
                                    <div class="">
                                        <div class="answer_help">
                                            <span>Is this question help?</span>
                                            <div class="ans_feed">
                                                <label for="answer_yes">Yes <i class="material-icons">thumb_up</i></label>
                                                <input type="radio" id="answer_yes" onclick="addAnswer(<?= $value->id ?>, 1)" name="answer" value="1">
                                            </div>
                                            <div class="ans_feed">
                                                <label for="answer_no">No <i class="material-icons">thumb_down</i></label>
                                                <input type="radio" id="answer_no" onclick="addAnswer(<?= $value->id ?>, 0)" name="answer" value="0">
                                            </div>
                                        </div>
                                    </div><div class="clearfix"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="faq-img-layout">
                        <div class="faq-img-content"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
