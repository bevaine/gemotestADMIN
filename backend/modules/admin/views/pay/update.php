<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\NPay */

$this->title = 'Редактирование: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Платежи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="npay-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
