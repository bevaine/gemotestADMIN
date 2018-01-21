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
$url = \yii\helpers\Url::to(['/admin/kontragents/ajax-kontragents-list']);

$initScript = <<< SCRIPT
    function (element, callback) {
        var id=\$(element).val();
        console.log(id);
        if (id !== "") {
            \$.ajax("{$url}?id=" + id, {
                dataType: "json"
            }).done(function(data) { callback(data.results);});
        }
    }
SCRIPT;
?>

<div class="nworkshift-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_aid')->textInput() ?>

    <?php
    echo $form->field($model, 'sender_key')->widget(Select2::classname(), [
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
            'initSelection' => new JsExpression($initScript)
        ],
    ])->label('Отделение');
    ?>

    <?php
    echo $form->field($model, 'sender_key_close')->widget(Select2::classname(), [
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
            'initSelection' => new JsExpression($initScript)
        ],
    ])->label('Отделение закрытия');
    ?>

    <?= $form->field($model, 'kkm')->textInput() ?>

    <?= $form->field($model, 'z_num')->textInput() ?>

    <?= Html::label("Дата открытия") ?>

    <?= DateTimePicker::widget([
        'name' => 'NWorkshift[open_date]',
        'type' => DateTimePicker::TYPE_INPUT,
        'value' => date("Y-m-d H:i:s", strtotime($model->open_date)),
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
        'value' => date("Y-m-d H:i:s", strtotime($model->close_date)),
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
