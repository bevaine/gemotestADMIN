<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\NewsBlog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-blog-form">

    <?php $form = ActiveForm::begin(); ?>

    <p>
        <label>Дата публикации:</label><br>
        <?php echo DatePicker::widget([
            'name' => 'publiched_at',
            'model' => $model,
            'attribute' => 'publiched_at',
            'dateFormat' => 'dd.MM.yyyy',
        ]);
        ?>
    </p>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['maxlength' => true, 'rows' => 15]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
