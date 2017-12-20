<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\NCashBalanceInLOFlow */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ncash Balance In Loflows', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ncash-balance-in-loflow-view">
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
            'cashbalance_id',
            'sender_key',
            'total',
            'date',
            'operation',
            'balance',
            'workshift_id',
            'operation_id',
        ],
    ]) ?>

</div>
