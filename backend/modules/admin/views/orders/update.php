<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrdersToExport */

$this->title = 'Update Orders To Export: ' . $model->AID;
$this->params['breadcrumbs'][] = ['label' => 'Orders To Exports', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->AID, 'url' => ['view', 'id' => $model->AID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="orders-to-export-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
