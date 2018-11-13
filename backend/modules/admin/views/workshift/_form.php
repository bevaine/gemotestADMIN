<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\NWorkshift */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$url1 = \yii\helpers\Url::to(['/admin/kontragents/ajax-kontragents-list']);
$url2 = \yii\helpers\Url::to(['/admin/logins/ajax-user-data-list']);

$initScript1 = <<< SCRIPT
    function (element, callback) {
        var id=\$(element).val();
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

<div class="nworkshift-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sender_key')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Наименование отделения'],
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
    ])->label('Отделение');
    ?>

    <?= $form->field($model, 'sender_key_close')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'Наименование отделения'],
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
    ])->label('Отделение закрытия');
    ?>

    <?= $form->field($model, 'user_aid')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'ФИО сотрудника'],
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
    ])->label('Пользователь:');
    ?>

    <?= $form->field($model, 'kkm')->textInput() ?>

    <?= $form->field($model, 'z_num')->textInput() ?>

    <?= Html::label("Дата открытия") ?>

    <?= DateTimePicker::widget([
        'name' => 'NWorkshift[open_date]',
        'type' => DateTimePicker::TYPE_INPUT,
        'value' => isset($model->open_date)
            ? date("Y-m-d  H:i:s", strtotime($model->open_date))
            : null,
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd hh:ii:ss'
        ]
    ]);
    ?><br>

    <?= Html::label("Дата закрытия") ?>

    <?= DateTimePicker::widget([
        'name' => 'NWorkshift[close_date]',
        'type' => DateTimePicker::TYPE_INPUT,
        'value' => isset($model->close_date)
            ? date("Y-m-d  H:i:s", strtotime($model->close_date))
            : null,
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd hh:ii:ss'
        ]
    ]);
    ?><br>

    <?= $form->field($model, 'not_zero_sum_start')->textInput() ?>

    <?= $form->field($model, 'not_zero_sum_end')->textInput() ?>

    <?= $form->field($model, 'amount_cash_register')->textInput() ?>

    <?= $form->field($model, 'error_check_count')->textInput() ?>

    <?= $form->field($model, 'error_check_total_cash')->textInput() ?>

    <?= $form->field($model, 'error_check_total_card')->textInput() ?>

    <?= $form->field($model, 'error_check_return_count')->textInput() ?>

    <?= $form->field($model, 'error_check_return_total_cash')->textInput() ?>

    <?= $form->field($model, 'error_check_return_total_card')->textInput() ?>

    <?= $form->field($model, 'file_name')->textInput() ?>

    <?= $form->field($model, 'code_1c')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
