<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use yii\web\JsExpression;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\NReturnOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$url = \yii\helpers\Url::to(['/admin/logins/ajax-user-data-list']);

$initScript = <<< SCRIPT
    function (element, callback) {
        var id=\$(element).val();
        if (id !== "") {
            \$.ajax("{$url}?id=" + id, {
                dataType: "json"
            }).done(function(data) { callback(data.results);});
        }
    }
SCRIPT;
?>

<div class="nreturn-order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent_id')->textInput() ?>

    <?= $form->field($model, 'parent_type')->textInput() ?>

    <?= Html::label("Дата возврата") ?>

    <?= DateTimePicker::widget([
        'name' => 'NReturnOrder[date]',
        'type' => DateTimePicker::TYPE_INPUT,
        'value' => date("Y-m-d H:i:s", strtotime($model->date)),
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd hh:ii:ss'
        ]
    ]);
    ?><br>

    <?= $form->field($model, 'order_num')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'total')->textInput() ?>

    <?= $form->field($model, 'user_id')->widget(Select2::classname(), [
        'options' => ['placeholder' => 'ФИО сотрудника'],
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
    ])->label('Пользователь:');
    ?>

    <?= $form->field($model, 'kkm')->textInput() ?>

    <?= $form->field($model, 'sync_with_lc_status')->textInput() ?>

    <?= Html::label("Дата обнов.") ?>

    <?= DateTimePicker::widget([
        'name' => 'NReturnOrder[last_update]',
        'type' => DateTimePicker::TYPE_INPUT,
        'value' => date("Y-m-d H:i:s", strtotime($model->last_update)),
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd hh:ii:ss'
        ]
    ]);
    ?><br>

    <?= Html::label("Дата синхр. с 1С") ?>

    <?= DateTimePicker::widget([
        'name' => 'NReturnOrder[sync_with_lc_date]',
        'type' => DateTimePicker::TYPE_INPUT,
        'value' => date("Y-m-d H:i:s", strtotime($model->sync_with_lc_date)),
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd hh:ii:ss'
        ]
    ]);
    ?><br>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
