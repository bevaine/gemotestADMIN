<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\NWorkshift */

$this->title = 'Редактирование: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Смены', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="nworkshift-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
