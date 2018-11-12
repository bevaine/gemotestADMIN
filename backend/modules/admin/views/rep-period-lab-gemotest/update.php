<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RepPeriodLabGemotest */

$this->title = 'Редактирование: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Мед. сообщества', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="rep-period-lab-gemotest-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
