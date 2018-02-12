<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\BranchStaff */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$url1 = \yii\helpers\Url::to(['/admin/logins/ajax-zabor-list']);
$url2 = \yii\helpers\Url::to(['/admin/kontragents/ajax-kontragents-list']);

$initScript1 = <<< SCRIPT
    function (element, callback) {
        var id=\$(element).val();
        console.log(id);
        if (id !== "") {
            \$.ajax("{$url1}?id=" + id, {
                dataType: "json"
            }).done(function(data) { callback(data.results);});
        }
    }
SCRIPT;

$initScript2 = <<< SCRIPT
    function (element, callback) {
        var id=\$(element).val();
        console.log(id);
        if (id !== "") {
            \$.ajax("{$url2}?id=" + id, {
                dataType: "json"
            }).done(function(data) { callback(data.results);});
        }
    }
SCRIPT;
?>

<div class="branch-staff-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //$form->field($model, 'first_name')->textInput() ?>

    <?php //$form->field($model, 'middle_name')->textInput() ?>

    <?php //$form->field($model, 'last_name')->textInput() ?>

    <?php
    echo $form->field($model, 'guid')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'ФИО сотрудника'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 2,
            'multiple' => false,
            'ajax' => [
                'url' => $url1,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {search:params.term}; }'),
                'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
            ],
            'initSelection' => new JsExpression($initScript1)
        ],
    ])->label('ФИО сотрудника');
    ?>

    <?php
    echo $form->field($model, 'sender_key')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Наименование отделения'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 2,
            'multiple' => false,
            'ajax' => [
                'url' => $url2,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {search:params.term}; }'),
                'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
            ],
            'initSelection' => new JsExpression($initScript2)
        ],
    ])->label('Отделение');
    ?>

    <?php
    $model->isNewRecord ? $params = ['options' => ['1'=>['selected'=>'selected']]] : $params = ['options' => null];
    echo $form->field($model, 'prototype')->dropDownList(\common\models\BranchStaffPrototype::getPrototypeList(), $params)
    ?>

    <?= Html::label("Дата открытия") ?>

    <?= DateTimePicker::widget([
        'name' => 'BranchStaff[date]',
        'type' => DateTimePicker::TYPE_INPUT,
        'value' => date("Y-m-d", strtotime($model->date)),
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);
    ?><br>

    <? //$form->field($model, 'personnel_number')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
