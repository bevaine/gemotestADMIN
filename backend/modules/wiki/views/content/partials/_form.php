<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
//use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model asinfotrack\yii2\wiki\models\Wiki */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(['enableClientValidation'=>false]); ?>

	<?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
    ]) ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

<?php ActiveForm::end(); ?>
