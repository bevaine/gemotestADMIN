<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MedReturnOrder */

$this->title = 'Update Med Return Order: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Возврат МИС', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
?>
<div class="med-return-order-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
