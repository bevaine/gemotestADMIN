<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model asinfotrack\yii2\wiki\models\Wiki */

$this->title = 'Редактирование Wiki: ' . ' ' . $model->title;
?>

<?= $this->render('partials/_form', [
	'model' => $model,
]) ?>
