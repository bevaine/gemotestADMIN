<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\RepPeriodLabGemotest */

$this->title = $model->contract;
$this->params['breadcrumbs'][] = ['label' => 'Мед. сообщества', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rep-period-lab-gemotest-view">

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
            'contract',
            'date_start',
            'date_end',
            'sender_id',
            'login',
            'pass',
            'date_active',
            'reward',
            'test_period',
            'deleted',
            'user_id',
        ],
    ]) ?>

</div>
