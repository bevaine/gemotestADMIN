<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GmsDevices */

$this->title = 'Привязка устройства';
$this->params['breadcrumbs'][] = ['label' => 'Устройства', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-devices-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
