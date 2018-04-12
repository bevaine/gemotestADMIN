<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GmsGroupDevices */

$this->title = 'Update Gms Group Devices: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Gms Group Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="gms-group-devices-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
