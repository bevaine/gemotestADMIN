<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\InputOrderZabor */

$this->title = $model->aid;
$this->params['breadcrumbs'][] = ['label' => 'Взятие биоматериала', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="input-order-iskl-issl-mszabor-view">

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->aid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->aid], [
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
            'aid',
            'OrderID',
            'DateIns',
            [
                'attribute' => 'last_name',
                'value' => isset($model->branchStaff) ? $model->branchStaff->last_name : null
            ],
            [
                'attribute' => 'first_name',
                'value' => isset($model->branchStaff) ? $model->branchStaff->first_name : null
            ],
            [
                'attribute' => 'middle_name',
                'value' => isset($model->branchStaff) ? $model->branchStaff->middle_name : null
            ],
            'MSZabor',
            'IsslCode',
        ],
    ]) ?>

</div>
