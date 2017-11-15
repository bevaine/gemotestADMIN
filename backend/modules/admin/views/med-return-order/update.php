<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MedReturnOrder */

$this->title = 'Update Med Return Order: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Med Return Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="med-return-order-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
