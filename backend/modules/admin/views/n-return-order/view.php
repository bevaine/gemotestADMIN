<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\NReturnOrder */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Возврат ЛИС', 'url' => ['index']];
?>
<div class="nreturn-order-view">

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
            'parent_id',
            'parent_type',
            'date',
            'order_num',
            'status',
            'total',
            'user_id',
            'kkm',
            'sync_with_lc_status',
            'last_update',
            'sync_with_lc_date',
        ],
    ]) ?>

</div>