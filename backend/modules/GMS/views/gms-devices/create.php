<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GmsDevices */

$this->title = 'Create Gms Devices';
$this->params['breadcrumbs'][] = ['label' => 'Gms Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-devices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
