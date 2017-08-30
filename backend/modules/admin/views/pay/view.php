<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\NPay */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Npays', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="npay-view">

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
            'date',
            'order_num',
            'order_data',
            'base_doc_id',
            'base_doc_type',
            'base_doc_date',
            'patient_id',
            'patient_fio',
            'patient_phone',
            'patient_birthday',
            'login_id',
            'login_key',
            'login_type',
            'login_fio',
            'sender_id',
            'sender_name',
            'pay_type',
            'cost',
            'discount_card',
            'discount_id',
            'discount_name',
            'discount_percent',
            'bonus',
            'discount_total',
            'total',
            'cito_factor',
            'bonus_balance',
            'printlist',
            'free_pay',
            'app_version',
            'kkm',
            'z_num',
            'pay_type_original',
        ],
    ]) ?>

</div>
