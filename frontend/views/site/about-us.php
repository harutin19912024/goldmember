<?php
/* @var $this yii\web\View */

use backend\models\Aboutus;
use backend\models\Team;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Goldmember') . ' | ' . Yii::t('app', 'About Us');

$about = Aboutus::find_One();
$teams = Team::find()->all();
?>

<!-- Hero Banner -->
<section class="position-relative main-banner bg-black-color">
    <div class="position-absolute top-0 start-0 left-0 w-100 h-100">
        <img class="w-100 h-100" src="/images/about-banner.svg" alt="About Us Banner" height="400" width="1442"/>
    </div>
    <div class="row w-100 container mx-auto position-relative zindex-offcanvas-backdrop h-100 px-3 px-md-0 d-flex align-items-end">
        <div class="col-md-5 col-12 px-0">
            <h1 class="display-6 primary-alternate-color text-uppercase px-0 mb-2 pb-2 title">
                <?= Yii::t('app', 'About Goldmember') ?>
            </h1>
            <p class="text-content fs-4 pb-1 white-color fw-normal mb-5 h-base">
                <?= Yii::t('app', 'Armenia\'s trusted platform for gold and precious metals — buy, sell and trade with confidence.') ?>
            </p>
        </div>
    </div>
</section>

<!-- Sub-heading -->
<section class="container breadcrumb-container py-2 px-sm-0">
    <div class="row px-0">
        <div class="col-sm-12">
            <div class="container">
                <div class="row d-flex justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1><?= Html::encode($about[0]['title'] ?? Yii::t('app', 'Gold Member')) ?></h1>
                        <p class="mb-0"><?= Yii::t('app', 'Congratulations, you finally found the top wholesale platform of gold, golden jewelry and diamonds in region.') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<?= $this->render('partials/breadcrumb', ['prev' => 'Home', 'prevUrl' => '/' . Yii::$app->language, 'current' => 'About Us']) ?>

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
