<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\NReturnOrder */

$this->title = 'Редактирование: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Возраты ЛИС', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="nreturn-order-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
