<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GmsSenders */

$this->title = 'Редактировать отделение: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Gms Senders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="gms-senders-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
