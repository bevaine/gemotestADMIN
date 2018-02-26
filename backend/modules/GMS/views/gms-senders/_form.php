<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use common\components\helpers\FunctionsHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\GmsSenders */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gms-senders-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $url = \yii\helpers\Url::to(['/GMS/gms-regions/ajax-regions-list']);
    echo $form->field($model, 'region_id')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Наименование региона'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'multiple' => false,
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {search:params.term}; }'),
                'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
            ],
            'initSelection' => new JsExpression(FunctionsHelper::AjaxInitScript($url))
        ],
    ])->label('Регион');


    $url = \yii\helpers\Url::to(['/admin/kontragents/ajax-kontragents-list']);
    echo $form->field($model, 'sender_key')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Наименование отделения'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'multiple' => false,
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {search:params.term}; }'),
                'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
            ],
            'initSelection' => new JsExpression(FunctionsHelper::AjaxInitScript($url))
        ],
    ])->label('Отделение');
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
