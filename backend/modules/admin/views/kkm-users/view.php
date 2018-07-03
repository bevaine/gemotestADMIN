<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\NKkmUsers */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи ККМ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nkkm-users-view">
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
            'kkm_id',
            'user_id',
            'login',
            'password',
            'user_type',
        ],
    ]) ?>

</div>
