<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $UserModel frontend\models\Customer */
/* @var $favorites array */

$this->title = Yii::t('app', 'Goldmember') . ' | ' . Yii::t('app', 'My Profile');

$initials = strtoupper(
    substr($UserModel->name ?? 'G', 0, 1) .
    substr($UserModel->surname ?? 'M', 0, 1)
);

?>

<!-- Hero Banner -->
<section class="position-relative main-banner bg-black-color">
    <div class="position-absolute top-0 start-0 left-0 w-100 h-100">
        <img class="w-100 h-100" src="/images/profile-banner.svg" alt="Profile Banner" height="400" width="1442"/>
    </div>
    <div class="row w-100 container mx-auto position-relative zindex-offcanvas-backdrop h-100 px-3 px-md-0 d-flex align-items-end">
        <div class="col-md-5 col-12 px-0">
            <h1 class="display-6 primary-alternate-color text-uppercase px-0 mb-2 pb-2 title">
                <?= Yii::t('app', 'My Account') ?>
            </h1>
            <p class="text-content fs-4 pb-1 white-color fw-normal mb-5 h-base">
                <?= Yii::t('app', 'Manage your profile, view your orders and track your favourites.') ?>
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
                        <h1><?= Yii::t('app', 'Welcome') ?>,
                            <?= Html::encode(ucfirst($UserModel->name ?? '')) ?>!</h1>
                        <p class="mb-0"><?= Yii::t('app', 'Gold Member') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<?= $this->render('/site/partials/breadcrumb', ['prev' => 'Home', 'prevUrl' => '/' . Yii::$app->language, 'current' => 'My Profile']) ?>

<!-- Profile Content -->
<section class="container py-5 px-sm-0">
    <div class="row g-4">

        <!-- ===== LEFT: Profile Card ===== -->
        <div class="col-lg-3 col-md-4">
            <div class="card border-0 shadow-sm text-center h-100" style="border-radius:12px; overflow:hidden;">

                <!-- Avatar -->
                <div class="py-4" style="background: linear-gradient(135deg,#0d2020 0%,#0a1818 100%);">
                    <div class="mx-auto d-flex align-items-center justify-content-center rounded-circle fw-bold"
                         style="width:90px;height:90px;font-size:2rem;background:#13B2AD;color:#fff;letter-spacing:2px;">
                        <?= Html::encode($initials) ?>
                    </div>
                    <h5 class="mt-3 mb-0 fw-semibold" style="color:#53E2D9;">
                        <?= Html::encode(ucfirst($UserModel->name ?? '') . ' ' . ucfirst($UserModel->surname ?? '')) ?>
                    </h5>
                    <span class="badge mt-2 px-3 py-1" style="background:#D4A017;color:#000;font-size:0.7rem;letter-spacing:1px;">
                        GOLD MEMBER
                    </span>
                </div>

                <!-- Quick info -->
                <div class="card-body text-start px-3 py-3">
                    <?php if (!empty($UserModel->email)): ?>
                        <div class="d-flex align-items-center gap-2 mb-2 text-muted small">
                            <i class="bi bi-envelope" style="color:#13B2AD;"></i>
                            <span class="text-truncate"><?= Html::encode($UserModel->email) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($UserModel->phone)): ?>
                        <div class="d-flex align-items-center gap-2 mb-2 text-muted small">
                            <i class="bi bi-telephone" style="color:#13B2AD;"></i>
                            <span><?= Html::encode($UserModel->phone) ?></span>
                        </div>
                    <?php endif; ?>

                    <hr class="my-3">

                    <!-- Nav links -->
                    <nav class="d-flex flex-column gap-1">
                        <a href="#profile-info" class="btn btn-sm text-start"
                           style="color:#13B2AD; border:1px solid #13B2AD20;">
                            <i class="bi bi-person me-2"></i><?= Yii::t('app', 'Account Info') ?>
                        </a>
                        <a href="#orders-tab" class="btn btn-sm text-start"
                           style="color:#D4A017; border:1px solid #D4A01720;">
                            <i class="bi bi-bag me-2"></i><?= Yii::t('app', 'Order History') ?>
                        </a>
                        <a href="#favorites-tab" class="btn btn-sm text-start"
                           style="color:#D4A017; border:1px solid #D4A01720;">
                            <i class="bi bi-heart me-2"></i><?= Yii::t('app', 'Favourites') ?>
                        </a>
                        <hr class="my-1">
                        <a href="<?= Url::to(['/site/logout']) ?>"
                           class="btn btn-sm text-start text-muted"
                           style="border:1px solid #eee;"
                           onclick="return confirm('<?= Yii::t('app', 'Log out?') ?>')">
                            <i class="bi bi-box-arrow-right me-2"></i><?= Yii::t('app', 'Logout') ?>
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- ===== RIGHT: Info + Tabs ===== -->
        <div class="col-lg-9 col-md-8">

            <!-- Account info card -->
            <div id="profile-info" class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
                <div class="card-header border-0 py-3 px-4"
                     style="background:linear-gradient(90deg,#0d2020,#0a1818); border-radius:12px 12px 0 0;">
                    <h5 class="mb-0 fw-semibold" style="color:#53E2D9;">
                        <i class="bi bi-person-circle me-2"></i><?= Yii::t('app', 'Contact Info') ?>
                    </h5>
                </div>
                <div class="card-body px-4 py-3">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-semibold" style="letter-spacing:1px; font-size:0.7rem;">
                                <?= Yii::t('app', 'First Name') ?>
                            </label>
                            <div class="border-bottom pb-2 mt-1 fw-medium">
                                <?= Html::encode(ucfirst($UserModel->name ?? '—')) ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-semibold" style="letter-spacing:1px; font-size:0.7rem;">
                                <?= Yii::t('app', 'Last Name') ?>
                            </label>
                            <div class="border-bottom pb-2 mt-1 fw-medium">
                                <?= Html::encode(ucfirst($UserModel->surname ?? '—')) ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-semibold" style="letter-spacing:1px; font-size:0.7rem;">
                                <?= Yii::t('app', 'Email') ?>
                            </label>
                            <div class="border-bottom pb-2 mt-1 fw-medium">
                                <?= Html::encode($UserModel->email ?? '—') ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small text-uppercase fw-semibold" style="letter-spacing:1px; font-size:0.7rem;">
                                <?= Yii::t('app', 'Phone Number') ?>
                            </label>
                            <div class="border-bottom pb-2 mt-1 fw-medium">
                                <?= Html::encode($UserModel->phone ?: '—') ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Tabs: Orders & Favourites -->
            <div class="card border-0 shadow-sm" style="border-radius:12px;">
                <div class="card-header border-0 px-0 pt-0" style="border-radius:12px 12px 0 0; overflow:hidden;">
                    <ul class="nav nav-tabs border-0" id="profileTabs" role="tablist"
                        style="background:#0a1818;">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-semibold px-4 py-3 border-0"
                                    id="orders-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#orders-pane"
                                    type="button" role="tab"
                                    style="color:#53E2D9; background:transparent; border-bottom:3px solid #13B2AD !important; border-radius:0;">
                                <i class="bi bi-bag me-2"></i><?= Yii::t('app', 'Order History') ?>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold px-4 py-3 border-0"
                                    id="favorites-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#favorites-pane"
                                    type="button" role="tab"
                                    style="color:#888; background:transparent; border-bottom:3px solid transparent; border-radius:0;">
                                <i class="bi bi-heart me-2"></i><?= Yii::t('app', 'Favourites') ?>
                                <?php if (!empty($favorites)): ?>
                                    <span class="badge rounded-pill ms-1" style="background:#D4A017;color:#000;font-size:0.65rem;">
                                        <?= count($favorites) ?>
                                    </span>
                                <?php endif; ?>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content" id="profileTabsContent">

                        <!-- Orders pane -->
                        <div class="tab-pane fade show active p-4" id="orders-pane" role="tabpanel">
                            <?= $this->render('history') ?>
                        </div>

                        <!-- Favourites pane -->
                        <div class="tab-pane fade p-4" id="favorites-pane" role="tabpanel">
                            <?= $this->render('favorites', ['products' => $favorites]) ?>
                        </div>

                    </div>
                </div>
            </div>

        </div><!-- /right col -->
    </div><!-- /row -->
</section>

<style>
/* Tab active state */
#profileTabs .nav-link.active {
    color: #53E2D9 !important;
    border-bottom-color: #13B2AD !important;
}
#profileTabs .nav-link:not(.active):hover {
    color: #D4A017 !important;
    border-bottom-color: #D4A01740 !important;
}
</style>
