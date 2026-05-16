<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<nav class="navbar" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <div class="menu">
                <span class="menu-item"></span>
                <span class="menu-item"></span>
                <span class="menu-item"></span>
            </div>
        </button>
        <a class="nav-logo" href="/"><?= Html::img('@web/images/logo.png'); ?></a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="navbar-nav nav-menu">
            <li><a href="<?php echo Url::to(['/']) ?>" data-subcategories="1"
                   class="<?php echo ($controller == 'site' && $action == 'index') ? 'active' : ''; ?>"><?= Yii::t('app', 'Home'); ?></a>
            </li>
            <li><a href="<?php echo Url::to(['product/index']) ?>" data-subcategories="1"
                   class="<?php echo ($controller == 'product' && $action == 'index') ? 'active' : ''; ?>"><?= Yii::t('app', 'Products'); ?></a>
            </li>
            <li><a href="" data-subcategories="1"
                   class="<?php echo ($controller == 'site' && $action == 'index') ? 'active' : ''; ?>"><?= Yii::t('app', 'Terms of use'); ?></a>
            </li>
            <li><a href="<?php echo Url::to(['/blog/']) ?>" data-subcategories="1"
                   class="<?php echo ($controller == 'site' && $action == 'index') ? 'active' : ''; ?>"><?= Yii::t('app', 'Blog'); ?></a>
            </li>
            <li><a href="<?php echo Url::to(['/faq']) ?>" data-subcategories="1"
                   class="<?php echo ($controller == 'site' && $action == 'index') ? 'active' : ''; ?>"><?= Yii::t('app', 'F.A.Q.'); ?></a>
            </li>
            <li><a href="" data-subcategories="1"
                   class="<?php echo ($controller == 'site' && $action == 'index') ? 'active' : ''; ?>"><?= Yii::t('app', 'About Us'); ?></a>
            </li>
        </ul>
        <div class="nav_panel">
            <div class="pull-left col-md-6 col-sm-6 col-xs-12 text-left">
                <ul>
                    <li class="drop_lang">
                        <div class="dropdown">
                            <i class="glyphicon glyphicon-globe"></i>
                            <?php foreach ($languages as $language): ?>
                                <?php if (Yii::$app->language == $language['short_code']): ?>
                                    <a class="dropdown-toggle" id="dropdownMenu1"
                                       data-toggle="dropdown"><?php echo $language['name']; ?></a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                <?php foreach ($languages as $language): ?>
                                    <?php if (Yii::$app->language != $language['short_code']): ?>
                                        <li role="presentation">
                                            <a role="menuitem" tabindex="-1" class="languages"
                                               href="<?= $url = Url::to(['/' . $currentUrl, 'language' => $language['short_code']]) ?>"
                                               value="<?php echo $language['short_code'] ?>"><?php echo $language['name'] ?></a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
                    <!--                                    <li class="drop_valut">-->
                    <!--                                        <div class="dropdown cur_valut">-->
                    <!--                                            <i class="glyphicon glyphicon-gbp"></i>-->
                    <!--                                            <a class="dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown">euro <i class="material-icons">keyboard_arrow_down</i></a>-->
                    <!--                                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">-->
                    <!--                                                <li role="presentation"><a class="currency" value="usd" role="menuitem" tabindex="-1" href="#"><span class="glyphicon glyphicon-usd"></span> USD</a></li>-->
                    <!--                                                <li role="presentation"><a class="currency" value="gbp" role="menuitem" tabindex="-1" href="#"><span class="glyphicon glyphicon-gbp-->
                    <!--				"></span> GBP</a></li>-->
                    <!--                                            </ul>-->
                    <!--                                        </div>-->
                    <!--                                    </li>-->
                </ul>
            </div>
            <div class="pull-right col-md-6 col-sm-6 col-xs-12 text-right">
                <ul>
                    <li class="head_support">
                        <a href="#"><i class="material-icons">headset_mic</i><?= Yii::t('app', 'SUPPORT'); ?> </a>
                    </li>
                    <li class="head_search">
                        <i class="material-icons">search</i>
                        <form class="search" role="search" method="get" action="/">
                            <input type="text" id="search_input"
                                   placeholder="<?= Yii::t('app', 'I\'m Looking for ...'); ?>">
                            <div id="search_result">
                                <ul></ul>
                            </div>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

    </div><!-- /.navbar-collapse -->
</nav>
<ul class="nav navbar-nav navbar-right">
    <li class="hidden-xs">
        <a id="customer-message" href="#" data-toggle="tooltip" data-placement="bottom" title="Message">
            <i class="material-icons">&#xE0E1;</i>
        </a>
    </li>
    <li>
        <a id="customer_card_link" href="javascript:void(0)">
            <i class="material-icons">shopping_cart</i> <span
                class="hidden-sm hidden-xs"><?= Yii::t('app', 'Cart'); ?> </span><span id="card_pods_cnt"
                                                                                       class="counter">0</span>
        </a>
    </li>
    <li class="customer_account_buttons">
        <?php if (!empty(Yii::$app->user->identity)): ?>
            <a href="<?php echo Url::to('/user/profile') ?>"><i class="material-icons visible-sm visible-xs">
                    &#xE7FD;</i>
                <div id="customer_login_link" class="hidden-sm hidden-xs lg_acc">My Account</div>
            </a>
            <a href="<?php echo Url::to('/site/logout') ?>" class="logout_btn" data-toggle="tooltip" title="Logout"
               data-placement="bottom"><i class="fa fa-sign-out"></i></a>

        <?php else: ?>
            <a href="<?php echo Url::to('/site/login') ?>"><i class="material-icons visible-sm visible-xs">&#xE7FD;</i>
                <div id="customer_login_link" class="hidden-sm hidden-xs">Login</div>
            </a>
        <?php endif; ?>
    </li>
</ul>