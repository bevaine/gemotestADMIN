<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\MedOrder */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Med Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="med-order-view">

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
            'patient_id',
            'user_id',
            'office_id',
            'status',
            'discount',
            'discount_name',
            'representative',
            'workshift_id',
            'guarantee_letter',
            'guarantee_letter_file_path',
            'guarantee_letter_file_name',
            'erp_order_id',
            'create_employee_guid',
            'create_user_id',
            'discount_type',
        ],
    ]) ?>

</div>
