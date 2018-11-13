<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use common\components\helpers\FunctionsHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\RepPeriodLabGemotest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rep-period-lab-gemotest-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'contract')->textInput(['disabled' => true]) ?>

    <?php
    $url = \yii\helpers\Url::to(['/admin/kontragents/ajax-kontragents-list']);
    echo $form->field($model, 'sender_id')->widget(Select2::class, [
        'options' => ['placeholder' => 'Наименование отделения'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 2,
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

    <?= $form->field($model, 'login')->textInput() ?>

    <?= $form->field($model, 'pass')->textInput() ?>

    <?= Html::label("Дата начало") ?>

    <?= DatePicker::widget([
        'name' => 'RepPeriodLabGemotest[date_start]',
        'type' => DateTimePicker::TYPE_INPUT,
        'value' => isset($model->date_start)
            ? date("Y-m-d", strtotime($model->date_start))
            : null,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    ?><br>

    <?= Html::label("Дата конец") ?>

    <?= DatePicker::widget([
        'name' => 'RepPeriodLabGemotest[date_end]',
        'type' => DateTimePicker::TYPE_INPUT,
        'value' => isset($model->date_end)
            ? date("Y-m-d", strtotime($model->date_end))
            : null,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    ?><br>

    <?= Html::label("Дата активации") ?>

    <?= DatePicker::widget([
        'name' => 'RepPeriodLabGemotest[date_active]',
        'type' => DateTimePicker::TYPE_INPUT,
        'value' => isset($model->date_active)
            ? date("Y-m-d", strtotime($model->date_active))
            : null,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    ?><br>

    <?= $form->field($model, 'reward')->textInput() ?>

    <?= $form->field($model, 'test_period')->textInput() ?>

    <?= Html::label("Удален") ?>

    <?= DatePicker::widget([
        'name' => 'RepPeriodLabGemotest[deleted]',
        'type' => DateTimePicker::TYPE_INPUT,
        'value' => isset($model->deleted)
            ? date("Y-m-d", strtotime($model->deleted))
            : null,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    ?><br>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
