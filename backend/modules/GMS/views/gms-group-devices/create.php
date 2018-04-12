<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GmsGroupDevices */

$this->title = 'Создать группу устройств';
$this->params['breadcrumbs'][] = ['label' => 'Группы устройств', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-group-devices-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
