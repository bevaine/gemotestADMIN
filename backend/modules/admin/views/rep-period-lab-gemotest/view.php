<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\RepPeriodLabGemotest */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rep Period Lab Gemotests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rep-period-lab-gemotest-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
