<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use common\models\NEncashmentDetail;

/* @var $this yii\web\View */
/* @var $model common\models\NEncashment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Инкассации', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="nencashment-view">
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
    <?php
    $columns = [
        'id',
        'sender_key',
        'total',
        'user_aid',
        'receipt_number',
        'receipt_file',
        'date',
        'code_1c',
        'status',
        [
            'label' => 'Движения ДС в ЛО',
            'value' => $model->cashBalanceInLOFlow ?  Html::a(
                $model->cashBalanceInLOFlow->operation,
                Url::to(["./cash-balance-lo/view", 'id' => $model->cashBalanceInLOFlow->id]),
                [
                    'title' => $model->cashBalanceInLOFlow->operation,
                    'target' => '_blank'
                ]): null,
            'visible' => $model->cashBalanceInLOFlow ? true : false,
            'format' => 'raw',
        ]
    ];

    if ($model->detail) {
        foreach ($model->detail as $row) {
            /** @var $row NEncashmentDetail */
            $columns[] = [
                'label' => $row->target == 'office_summ' ? 'Приход в отделение' : 'Приход по ККМ:'.$row->target,
                'value' => $row->total
            ];
        }
    }
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $columns,
    ]) ?>

</div>
