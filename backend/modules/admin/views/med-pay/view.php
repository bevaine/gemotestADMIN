<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\MedPay */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Med Pays', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="med-pay-view">

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
            'order_id',
            'patient_id',
            'patient_fio',
            'patient_phone',
            'patient_birthday',
            'user_id',
            'user_username',
            'office_id',
            'office_name',
            'pay_type',
            'cost',
            'discount_total',
            'total',
            'printlist',
            'user_fio',
            'free_pay',
            'base_doc_type',
            'base_doc_id',
            'is_virtual',
            'kkm',
            'z_num',
            'pay_type_original',
        ],
    ]) ?>

</div>
