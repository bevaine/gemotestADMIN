<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MedReturnWithoutItem */

$this->title = 'Update Med Return Without Item: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Med Return Without Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="med-return-without-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
