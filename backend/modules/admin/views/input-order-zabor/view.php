<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\InputOrderZabor */

$this->title = $model->aid;
$this->params['breadcrumbs'][] = ['label' => 'Input Order Iskl Issl Mszabors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="input-order-iskl-issl-mszabor-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->aid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->aid], [
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
            'IsslCode',
            'MSZabor',
            'DateIns',
        ],
    ]) ?>

</div>
