<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model asinfotrack\yii2\wiki\models\Wiki */

$this->title = 'Создать Wiki';
?>

<?= $this->render('partials/_form', [
	'model' => $model,
]) ?>
