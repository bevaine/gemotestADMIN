<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\NEncashment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="nencashment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sender_key')->textInput() ?>

    <?= $form->field($model, 'user_aid')->textInput() ?>

    <?= $form->field($model, 'receipt_number')->textInput() ?>

    <?= $form->field($model, 'receipt_file')->textInput() ?>

    <?= Html::label("Дата") ?>

    <?= DateTimePicker::widget([
        'name' => 'NEncashment[date]',
        'type' => DateTimePicker::TYPE_INPUT,
        'value' => date("Y-m-d H:i:s", strtotime($model->date)),
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd hh:ii:ss'
        ]
    ]);
    ?><br>

    <?= $form->field($model, 'code_1c')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?php
    if ($model->detail) {
        foreach ($model->detail as $row) { ?>
            <p>
            <?= Html::label($row->target == 'office_summ' ? 'Приход в отделение' : 'Приход по ККМ:'.$row->target) ?>
            <?= Html::textInput('arrDetail['.$row->id.']', $row->total, ['class' => 'form-control']) ?>
            </p>
            <?php
        }
    }
    ?>

    <?= $form->field($model, 'total')->textInput(['readonly' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
