<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\GmsHistory */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Gms Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-history-view">

    <p>
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
            'pls_id',
            'device_id',
            'created_at',
            'status',
            [
                'attribute' => 'log_text',
                'value' => implode("<br>", unserialize($model->log_text)),
                'format' => 'html'
            ]
        ],
    ]) ?>

</div>
