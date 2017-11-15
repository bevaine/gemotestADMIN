<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\NReturnOrder */

$this->title = 'Update Nreturn Order: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Nreturn Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="nreturn-order-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
