<?php
use backend\models\Donate;
use backend\models\Files;
use backend\models\PowerOfPenny;
use backend\models\TrPowerOfPenny;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$donates = Donate::find()->all();
$pageData = PowerOfPenny::find()->one();
$images = Files::find()->where(['category_id' => $pageData->id, 'category' => 'power-of-penny'])->all();
//echo "<pre>"; print_r($pageData);die;
///images/donate.png
?>

<section class="position-relative main-banner bg-black-color">
    <div class="position-absolute top-0 start-0 left-0 w-100 h-100">
        <img class="w-100 h-100" src="/images/donate.jpg" alt="Banner Image" height="400" width="1442"/>
    </div>
    <div class="row w-100 container mx-auto position-relative zindex-offcanvas-backdrop h-100 px-3 px-md-0 d-flex align-items-end">
        <div class="col-md-5 col-12 px-0">
            <h1 class="display-6 primary-alternate-color text-uppercase px-0 mb-2 pb-2 title"><?=Yii::t('app', 'Together, We Can Do More')?></h1>
            <p class="text-content fs-4 pb-1 white-color fw-normal mb-5 h-base ">
                <?=Yii::t('app', 'Join our community of supporters and help build a better future for everyone.')?></p>
        </div>
    </div>
</section>

<section class="container breadcrumb-container py-2 px-sm-0">
    <div class="row px-0">
        <div class="col-sm-12">
           <div class="container">
             <div class="row d-flex justify-content-center text-center">
                <div class="col-lg-8">
                   <h1><?=Yii::t('app', 'Make Your Donation')?></h1>
                   <p class="mb-0"><?=Yii::t('app', 'Make the world better with your donation.')?></p>
                </div>
             </div>
          </div>
        </div>
    </div>
</section>

<?=$this->render('partials/breadcrumb', ['prev'=>'Home', 'current'=>'Donate']);?>


   <div class="row">
      <section class="donation-form">
      	<?php foreach($images as $image):?>
      	 <?php
        	$imagePath = Yii::$app->params['adminUrl']. 'uploads/power-of-penny/news/' . $pageData->id .'/'. $image->path;
        ?>
      	<div class="news-item-image img-box">
                                    
                                </div>
      	<?php endforeach;?>
      
       </section>
  </div>


<!-- End Page Title -->
<div class="container" style="margin-top: 60px; padding-bottom: 100px;">
   <div class="row">
        <div class="col-lg-12">
            <div class="bg-light donation-form rounded-3 shadow-sm">
                <?php foreach($donates as $donate):?>
                    <div class="col-lg-6" style="display: inline-block;">
                        <p style="float:left"><?=$donate->bank_name?> : <?=$donate->bank_account?></p>
                    </div><br>
                <?php endforeach;?>
            </div>
        </div>
  </div>
  

   
   <div class="row">
      <section class="donation-form">
          
            <h2>Donate Now</h2>
            <?php $form = ActiveForm::begin(['id' => 'donate-form']); ?>
                
                
                <div class="form-group mb-3">
                    <?= $form->field($model, 'name',['template' => '{input}{error}'])->textInput(['placeholder' => Yii::t('app', 'Name')])->label(Yii::t('app', 'Name')) ?>
                </div>
                <div class="form-group mb-3">
                    <?= $form->field($model, 'email',['template' => '{input}{error}'])->textInput(['placeholder' => Yii::t('app', 'Email')])->label(Yii::t('app', 'Email')) ?>
                </div>
                <div class="form-group mb-3">
                    <?= $form->field($model, 'phone',['template' => '{input}{error}'])->textInput(['placeholder' => Yii::t('app', 'Phone'), 'type' => 'tel'])->label(Yii::t('app', 'Phone')) ?>
                </div>
                <div class="form-group mb-4">
                    <label class="form-label fw-bold"><?=Yii::t('app', 'Funding Amount')?>($)</label>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                      <button type="button" class="button primary-button donate-button" onclick="setAmount(5000)">5.000</button>
                      <button type="button" class="button primary-button donate-button" onclick="setAmount(10000)">10.000</button>
                      <button type="button" class="button primary-button donate-button" onclick="setAmount(20000)">20.000</button>
                      <button type="button" class="button primary-button donate-button" onclick="setAmount(50000)">50.000</button>
                      <input type="number" class="form-control" id="custom-amount" name="DonateForm[amount]" placeholder="Custom amount" style="max-width: 170px;">
                    </div>
                  </div>
                <div class="form-group mb-3">
                    <?= $form->field($model, 'message', ['template' => '{label}{input}{error}'])
                        ->textarea(['rows' => 4, 'class' => 'form-control'])
                        ->label(Yii::t('app', 'Message (optional)'), ['class' => 'form-label fw-bold']) ?>
                </div>
                <div class="form-group text-center">
                    <?= Html::submitButton(Yii::t('app', 'Donate'), ['class' => 'btn btn-lg button primary-button donate-button', 'name' => 'donate-button']) ?>
                </div>
                
            <?php ActiveForm::end(); ?>
            
             
        </section>
  </div>
</div>