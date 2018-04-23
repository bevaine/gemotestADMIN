<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataArr array */

$this->title = 'Редактирование группы: '.$dataArr['group_name'];
$this->params['breadcrumbs'][] = ['label' => 'Группы устройств', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $dataArr['group_name'], 'url' => ['view', 'group_id' => $dataArr['group_id']]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="gms-group-devices-update">

    <?= $this->render('_form', [
        'dataArr' => $dataArr,
    ]) ?>

</div>
