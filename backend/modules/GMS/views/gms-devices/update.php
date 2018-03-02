<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GmsDevices */

$this->title = 'Редактирование устройства ID: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Gms Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="gms-devices-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
