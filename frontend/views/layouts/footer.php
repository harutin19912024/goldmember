<?php
/**
 * string $currentUrl
 */
?>
<footer class="bg-secondary-color gray-color py-4 footer">
    <div class="container">
        <div class="footer-rows">
            <!-- Contact Section -->
            <div class="footer-col col-one">
                <div class="w-auto px-0">
                    <a href="/<?=Yii::$app->language?>" class="logo">
                        <img src="/images/icons/logo.svg" alt="Logo" height="32" width="32">
                        <span><?=Yii::t('app', 'Goldmember')?></span>
                    </a>
                </div>
                <p class="info-text"><?=Yii::t('app', 'Footer About Text')?></p>
                <ul class="px-0 d-flex justify-content-start social-buttons">
                    <?php foreach($socialLinks as $social):?>
                        <li class="social-button"><a href="<?=$social->link?>" class="social" target="_blank"> <i class="bi bi-<?=$social->social_type?>"></i> </a></li>
                    <?php endforeach;?>
                </ul>
            </div>

            <!-- Pages Links -->
            <div class="footer-col col-two">
                <p class="title"><?= Yii::t('app', 'PAGES'); ?></p>
                <ul class="links">
                    <li>
                        <a href="/<?=Yii::$app->language?>" class="link">
                            <?= Yii::t('app', 'Home'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="/<?=Yii::$app->language?>/about-us" class="link">
                            <?= Yii::t('app', 'About'); ?>
                        </a>
                    </li>
                    <li><a href="/<?=Yii::$app->language?>/news" class="link"><?= Yii::t('app', 'News'); ?></a></li>
                    <li><a href="#contact" class="link"><?= Yii::t('app', 'Contact'); ?></a></li>
                </ul>
            </div>

            <!-- Useful Links -->
            <div class="footer-col col-three">

                <p class="title">USEFUL LINKS</p>
                <ul class="links">
                    <li><a href="#" class="link"><?= Yii::t('app', 'Power of penny'); ?></a></li>
                    <li><a href="#" class="link"><?= Yii::t('app', 'Auction'); ?></a></li>
                    <li><a href="#" class="link"><?= Yii::t('app', 'Best Offer'); ?></a></li>
                </ul>
            </div>

            <!-- Contacts (Right Side) -->
            <div class="footer-col col-four">
                <p class="title"><?= Yii::t('app', 'Contact'); ?></p>

                <div class="contact-line">
                    <a href="mailto:<?=$settings[0]['site_email']?>"><?=$settings[0]['site_email']?></a>
                </div>
                <?php foreach(explode(',', $settings[0]['site_phone']) as $phone):?>
                    <div class="contact-line">
                        <a href="tel:+37494261605">(+374) 94 261 605</a>
                    </div>
                <?php endforeach;?>
            </div>
        </div>


    </div>
    <!-- Copyright -->
    <div class="text-center bg-white-color copy">
        <p class="mb-0">&copy; <?=$settings[0]['site_title']?> - <?= Yii::t('app', 'All rights reserved'); ?></p>
    </div>
</footer>
