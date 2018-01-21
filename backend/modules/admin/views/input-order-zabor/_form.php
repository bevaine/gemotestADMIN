<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\InputOrderZabor */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$url = \yii\helpers\Url::to(['/admin/logins/ajax-zabor-list']);

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

<div class="input-order-iskl-issl-mszabor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'OrderID')->textInput() ?>

    <?= $form->field($model, 'IsslCode')->textInput() ?>

    <?= Html::label("Дата добавления") ?>

    <?= DateTimePicker::widget([
        'name' => 'InputOrderZabor[DateIns]',
        'type' => DateTimePicker::TYPE_INPUT,
        'value' => date("Y-m-d H:i:s", strtotime($model->DateIns)),
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd hh:ii:ss'
        ]
    ]);
    ?><br>

    <?php
    echo $form->field($model, 'MSZabor')->widget(Select2::classname(), [
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
    ])->label('ФИО сотрудника взявшего БМ:');
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
