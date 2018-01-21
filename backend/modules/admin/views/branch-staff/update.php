<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BranchStaff */

$this->title = 'Редактирование: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Работающие в смене', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="branch-staff-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
