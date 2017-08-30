<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\NWorkshift */

$this->title = 'Update Nworkshift: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Nworkshifts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="nworkshift-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
