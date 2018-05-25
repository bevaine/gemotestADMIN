<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\GmsDevices */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Gms Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-devices-view">

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
            'device',
            [
                'value' => !empty($model->name) ? $model->name : null,
                'attribute' => 'name'
            ],
            'IP',
            [
                'value' => !empty($model->regionModel) ? $model->regionModel->region_name : null,
                'attribute' => 'region_id'
            ],
            [
                'value' => !empty($model->senderModel) ? $model->senderModel->sender_name : null,
                'attribute' => 'sender_id'
            ],
            [
                'value' => !empty($model->playListOutModel) ? $model->playListOutModel->name : null,
                'attribute' => 'current_pls_id'
            ],
            [
                'value' => \common\models\GmsDevices::getAuthStatus($model->auth_status),
                'attribute' => 'auth_status'
            ],
            'created_at:datetime',
            'last_active_at:datetime',
        ],
    ]) ?>

</div>
