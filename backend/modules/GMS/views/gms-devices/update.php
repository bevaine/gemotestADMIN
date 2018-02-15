<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GmsDevices */

$this->title = 'Update Gms Devices: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Gms Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="gms-devices-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
