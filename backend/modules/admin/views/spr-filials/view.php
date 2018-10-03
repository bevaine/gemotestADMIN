<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SprFilials */

$this->title = $model->AID;
$this->params['breadcrumbs'][] = ['label' => 'Запись на прием: Отделения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spr-filials-view">

    <p>
        <?= Html::a('Сохранить', ['update', 'id' => $model->AID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->AID], [
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
            'AID',
            'Fid',
            'Fkey',
            'Fname',
            'Type',
        ],
    ]) ?>

</div>
