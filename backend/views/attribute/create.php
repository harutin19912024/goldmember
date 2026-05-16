<?php

use yii\helpers\Html;
use backend\models\Category;

/* @var $this yii\web\View */
/* @var $model backend\models\Attribute */

$this->title = Yii::t('app', 'Create Filter');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Filter'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attribute-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Category::find()->count() > 0): ?>
        <?=
        $this->render('_form', [
            'model' => $model,
            'defoultId' => $defoultId,
            'attributeCategory' => $attributeCategory,
        ])
        ?>
<?php else: ?>
        <div class="alert alert-danger">
            <strong>Danger!</strong> First need to create <a href="/<?php echo Yii::$app->language?>/category/create">category</a>
        </div>
<?php endif; ?>
</div>
