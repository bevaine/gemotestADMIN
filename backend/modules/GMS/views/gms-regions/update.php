<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GmsRegions */

$this->title = 'Редактирование регионы: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Gms Regions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="gms-regions-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
