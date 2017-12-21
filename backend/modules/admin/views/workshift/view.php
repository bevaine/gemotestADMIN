<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\NWorkshift */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Nworkshifts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nworkshift-view">
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

    <p>
        <?= Html::a('Создать инкассацию', Url::to(["./encashment/create-encashment", 'workshift_id' => $model->id]), ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_aid',
            'sender_key',
            'kkm',
            'z_num',
            'open_date',
            'close_date',
            'not_zero_sum_start',
            'not_zero_sum_end',
            'amount_cash_register',
            'sender_key_close',
            'error_check_count',
            'error_check_total_cash',
            'error_check_total_card',
            'error_check_return_count',
            'error_check_return_total_cash',
            'error_check_return_total_card',
            'file_name',
            'code_1c',
        ],
    ]) ?>

</div>
