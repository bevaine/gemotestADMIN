<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\MedReturnWithoutItem */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Возврат без номенклатуры (МИС)', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="med-return-without-item-view">
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
    if (!isset($model->parent_id)) {
        echo '<p>'.Html::a('{Создать родителя}', ['create-parent', 'id' => $model->id], ['class' => 'btn btn-info']).'</p>';
    }
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'parent_id',
            'order_num',
            'total',
            'date',
            'pay_type',
            'kkm',
            'z_num',
            'comment:ntext',
            'path_file',
            'base',
            'user_aid',
            'code_1c',
        ],
    ]) ?>

</div>
