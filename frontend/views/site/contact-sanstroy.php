<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use backend\models\Sitesettings;

$this->title = Yii::t('app','Contact');
$this->params['breadcrumbs'][] = $this->title;
$settings = Sitesettings::find_One();
?>

<div class="container">
	<div class="row">
	<ul class="nav-product">
                            <li class="active">
                                <a href="/<?=Yii::$app->language?>">Главная</a>
                            </li>
                            <li>
                                <a href="javascrip:;"><?=Yii::t('app','Contact')?></a>
                            </li>
                        </ul>
	</div>
    <div class="row wrapper-main">
        <div class="col-md-6">
            <div class="well well-sm">
			<?php $form = ActiveForm::begin(['id' => 'contact-form','class'=>'form-horizontal']); ?>
                    <fieldset>
                        <legend class="text-center header">Свяжитесь с нами</legend>
                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-1">
                                <input id="fname" name="name" type="text" placeholder="Имя" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-1">
                                <input id="lname" name="name" type="text" placeholder="Фамилия" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-1">
                                <input id="email" name="email" type="text" placeholder="Адрес электронной почты" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-1">
                                <input id="phone" name="phone" type="text" placeholder="Телефон" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-1">
                                <textarea class="form-control" id="message" name="subject" placeholder="<?php echo Yii::t('app','Your Message Here')?>" rows="7"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 text-center">
							<?= Html::submitButton('Отправить', ['class' => 'btn btn-primary btn-lg', 'name' => 'contact-button']) ?>
                            </div>
                        </div>
                    </fieldset>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div>
                <div class="panel panel-default" style="height: 445px;">
                    <div class="text-center header">Наш Адрес</div>
                    <div class="panel-body text-center" style='color:black;'>
                        <div>
						 г. Москва,Каширское шоссе 19 к1 пав 3-D119
                        <?php $phone = explode(',',json_decode($settings[0]['site_phone'])[0]);?>
								<?php foreach($phone as $ph):?>
								<p>Tel:<?=$ph?></p>
								<?php endforeach;?>
                        <?=$settings[0]['site_email']?><br />
                        </div>
                        <hr />
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="row wrapper-main">
	<div class="map">
	<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2250.3804794652806!2d37.62918941592709!3d55.66498358052994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x414ab365b58b1845%3A0xfc1f274427b74276!2sSANSTROY!5e0!3m2!1sen!2s!4v1524576609682" width="1140" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>
	</div>
	</div>
</div>

<?php echo $this->registerCss('
    .map {
        min-width: 300px;
        min-height: 300px;
        width: 100%;
        height: 100%;
    }

    .header {
        background-color: #F5F5F5;
        color: #36A0FF;
        height: 70px;
        font-size: 27px;
        padding: 10px;
    }
'); ?>
