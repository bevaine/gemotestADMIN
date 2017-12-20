<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\NEncashment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Nencashments', 'url' => ['index']];
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

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
                'value' => function($model) {
                    /** @var $model \common\models\NEncashment*/
                    return $model->cashBalanceInLOFlow ? Html::a(
                        $model->cashBalanceInLOFlow->operation,
                        Url::to(["./cash-balance-lo/view", 'id' => $model['id']]),
                        [
                            'title' => $model->cashBalanceInLOFlow->operation,
                            'target' => '_blank'
                        ]
                    ) : null;
                },
                'visible' => $model->cashBalanceInLOFlow ? true : false,
                'format' => 'raw',
            ]
        ],
    ]) ?>

</div>
