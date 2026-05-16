<?php
/* @var $this yii\web\View */

use backend\models\Aboutus;
use backend\models\Team;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Goldmember') . ' | ' . Yii::t('app', 'About Us');

$about = Aboutus::find_One();
$teams = Team::find()->all();
?>

<!-- Page Title -->
<div class="page-title dark-background" style="background-image: url(/img/cta-bg.jpg); background-size: cover; background-position: center;">
    <div class="container">
        <div class="heading">
            <h1><?= Html::encode($about[0]['title']) ?></h1>
        </div>
        <nav aria-label="breadcrumb">
            <ol>
                <li><a href="/"><?= Yii::t('app', 'Home') ?></a></li>
                <li><?= Yii::t('app', 'About Us') ?></li>
            </ol>
        </nav>
    </div>
</div><!-- /Page Title -->

<!-- About Section -->
<section id="about" class="about section light-background">

    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row align-items-xl-stretch">

            <div class="col-xl-5 position-relative">
                <img src="/img/about.jpg" class="img-fluid rounded shadow" alt="About Us" style="width:100%; height:100%; object-fit:cover; min-height:300px;">
            </div>

            <div class="col-xl-7 d-flex flex-column justify-content-center ps-xl-5 pt-4 pt-xl-0">
                <div class="content">
                    <h3><?= Html::encode($about[0]['title']) ?></h3>
                    <p class="fst-italic">
                        <?= $about[0]['short_description'] ?>
                    </p>
                    <?php if (!empty($about[0]['description'])): ?>
                        <div class="mt-3">
                            <?= $about[0]['description'] ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

</section><!-- /About Section -->

<!-- Team Section -->
<section id="team" class="team section">

    <div class="container section-title" data-aos="fade-up">
        <h2><?= Yii::t('app', 'Our Team') ?></h2>
        <p><?= Yii::t('app', 'Meet the professionals behind Goldmember') ?></p>
    </div>

    <div class="container">
        <div class="row gy-4">
            <?php foreach ($teams as $team): ?>
                <?php
                $teamImage = Yii::$app->params['adminUrl'] . 'uploads/images/team/' . $team['id'] . '/' . $team['image'];
                ?>
                <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="100">
                    <div class="member w-100">
                        <img src="<?= Html::encode($teamImage) ?>" class="img-fluid" alt="<?= Html::encode($team['fname'] . ' ' . $team['sname']) ?>">
                        <div class="member-content">
                            <h4><?= Html::encode($team['fname'] . ' ' . $team['sname']) ?></h4>
                            <span><?= Html::encode($team['profession']) ?></span>
                            <div class="social">
                                <a href="#"><i class="bi bi-twitter-x"></i></a>
                                <a href="#"><i class="bi bi-facebook"></i></a>
                                <a href="#"><i class="bi bi-instagram"></i></a>
                                <a href="#"><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</section><!-- /Team Section -->
