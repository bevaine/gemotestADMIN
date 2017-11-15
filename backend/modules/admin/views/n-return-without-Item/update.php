<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\NReturnWithoutItem */

$this->title = 'Update Nreturn Without Item: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Nreturn Without Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="nreturn-without-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
