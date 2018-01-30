<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MedOrder */

$this->title = 'Update Med Order: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Med Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="med-order-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
