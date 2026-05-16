<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Product;
use yii\helpers\Url;
use kartik\select2\Select2;
use common\models\Language;
use backend\models\Category;
use backend\models\AttributeCategory;

$languages = Language::find()->asArray()->all();

/* @var $this yii\web\View */
/* @var $model backend\models\Attribute */
/* @var $form yii\widgets\ActiveForm */

$template = '{label}<div class="">{input}{error}</div>';
$categories_model = new Category();
$categories = $categories_model->getAllCategories();
$category = [];
if (!$model->isNewRecord) {
    $attrCategory = AttributeCategory::find()->where(['attribute_id' => $model->id])->all();
    if (!empty($attrCategory)) {
        foreach ($attrCategory as $cat) {
            $category[$cat->id] = $cat->category_id;
        }
    }
}
?>
<?php
if (!$model->isNewRecord) {
    $formId = 'attrUpdate';
    $action = '/attribute/update?id=' . $model->id;
} else {
    $formId = 'attrCreate';
    $action = '/attribute/create';
}
?>
<div class="attributes-form">
    <?= Html::a(Yii::t('app', 'Back to list'), ['/attribute/index'], ['class' => 'btn btn-primary mb15']) ?>
    <div class="panel sort-disable mb50" id="p2" data-panel-color="false" data-panel-fullscreen="false" data-panel-remove="false" data-panel-title="false">
        <div class="panel-heading">
            <span style="float: left;" class="panel-controls"><a href="#" class="panel-control-loader"></a><a href="#" style="margin-left: 5px" class="panel-control-collapse"></a></span>
            <ul class="nav panel-tabs-border panel-tabs">
                <?php
                if (!$model->isNewRecord) {
                    foreach ($languages as $value):
                        ?>
                        <li class="<?php
                if ($value['is_default']) {
                    $defoultId = $value['id'];
                    echo 'active';
                }
                        ?>">
                            <a href="#tab_<?php echo $value['id'] ?>"  data-toggle="tab" onclick="editAttributeTr(<?php echo $value['id']; ?>,<?php echo $model->id; ?>,<?php echo $value['is_default']; ?>)" >
                                <span class="flag-xs flag-<?php echo $value['short_code'] ?>"></span>
                            </a>
                        </li>
                        <?php
                    endforeach;
                }
                ?>
            </ul>
        </div>
        <div class="panel-body"  style="display: block;">
            <div class="tab-content pn br-n admin-form">
                <div class="tab-pane" id="tr_attribute">

                </div>
                <div class="tab-pane active" id="tab_<?php echo $defoultId; ?>">
                    <?php
                    $form = ActiveForm::begin([
                                'action' => [$action],
                                'id' => $formId,
                    ]);
                    ?>  
                    <div class="tab-content row">
                        <div class="col-md-4">
                            <?=
                                    $form->field($model, 'name', ['template' => $template])
                                    ->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Attribute Name')])->label(false)
                            ?>
                        </div>
                        <div class="col-md-4">
                            <?=
                            $form->field($attributeCategory, 'category_id')->widget(Select2::classname(), [
                                'data' => $categories,
                                'language' => Yii::$app->language,
                                'options' => [
                                    'placeholder' => Yii::t('app', 'Select Category') . '...',
                                    'value' => $category,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'multiple' => true,
                                ],
                                'pluginLoading' => false,
                            ])->label(false);
                            ?>
                        </div>
                        <div class="col-md-4">
                            <?=
                            $form->field($model, 'parent_id', ['template' => '<div class="col-md-12" style="padding: 0"><label for="customer-name" class="field prepend-icon">
                                                {input}</label>{error}</div>'
                                    ]
                            )->widget(Select2::classname(), [
                                'data' => $model->getParentAttributes(),
                                'language' => Yii::$app->language,
                                'options' => ['placeholder' => 'Выберите родительский атрибут ...'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'multiple' => false,
                                ],
                                'pluginLoading' => false,
                            ])->label(false);
                            ?>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <?=
                        Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
                            'class' => $model->isNewRecord ? 'btn btn-sm btn-primary pull-right ' : 'btn btn-sm btn-success pull-right',
                            'id' => $formId,
                            'type' => 'button'
                        ])
                        ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>






