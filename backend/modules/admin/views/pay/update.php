<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\NPay */

$this->title = 'Update Npay: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Npays', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="npay-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
