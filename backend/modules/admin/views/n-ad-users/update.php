<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\NAdUsers */

$this->title = 'Update Nad Users: ' . $model->gs_id;
$this->params['breadcrumbs'][] = ['label' => 'Nad Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->gs_id, 'url' => ['view', 'id' => $model->gs_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="nad-users-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
