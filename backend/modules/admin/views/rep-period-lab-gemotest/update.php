<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RepPeriodLabGemotest */

$this->title = 'Редактирование: ' . $model->contract;
$this->params['breadcrumbs'][] = ['label' => 'Мед. сообщества', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->contract, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="rep-period-lab-gemotest-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
