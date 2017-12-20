<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\NEncashment */

$this->title = 'Редактирование: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Инкассации', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="nencashment-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
