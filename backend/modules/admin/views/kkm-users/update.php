<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\NKkmUsers */

$this->title = 'Update Nkkm Users: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Nkkm Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="nkkm-users-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
