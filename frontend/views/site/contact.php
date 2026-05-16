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
$settings = $settings[0];
$phone = json_decode($settings['site_phone']);
?>
<section class="breadcumb-area bg-img" style="background-image: url(/img/bg-img/hero1.jpg);">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12">
                    <div class="breadcumb-content">
                        <h3 class="breadcumb-title"><?=$this->title?></h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
	<section class="south-contact-area section-padding-100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                   
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-4">
                    <div class="content-sidebar">
                        <div class="weekly-office-hours">
                            
                        </div>
                        <div class="address mt-30">
                           
                        </div>
                        <div class="social-contact">
                            <ul class="social">
                                <li><a href=""><i class="fa fa-facebook"></i> </a></li>
                                <li><a href=""><i class="fa fa-instagram"></i></a></li>
                                <li><a href=""><i class="fa fa-youtube"></i></a></li>
                                <li><a href=""><i class="fa fa-linkedin"></i> </a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="contact-form">
                        <form action="#" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control" name="text" id="contact-name" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <input type="number" class="form-control" name="number" id="contact-number" placeholder="Phone*">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" id="contact-email" placeholder="Email*">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="message" id="message" cols="30" rows="10" placeholder="Subject*"></textarea>
                            </div>
                            <button type="submit" class="btn south-btn">Send</button>
                        </form>
                    </div>
                </div>
                
                <div class="col-12 col-lg-4">
                    <div class="footer-widget-area mb-100">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Google Maps -->
    <div class="map-area">
        <div class="">
            <div class="row">
                <div class="col-12">
                    <div id="googleMap" class="googleMap"></div>
                </div>
            </div>
        </div>
    </div>