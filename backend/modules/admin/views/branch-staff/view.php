<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\BranchStaff */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Branch Staff', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branch-staff-view">

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'first_name',
            'middle_name',
            'last_name',
            'guid',
            'sender_key',
            'prototype',
            'date',
            'personnel_number',
        ],
    ]) ?>

</div>
