<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SprFilialsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Запись на прием: Отделения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spr-filials-index">
    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'AID',
            'Fkey',
            'Fname',
            [
                'attribute' => 'Type',
                'filter' => \common\models\Logins::getTypesArray(),
                'format' => 'text',
                'value' => function ($model) {
                    /** @var \common\models\SprFilials $model */
                    if (array_key_exists($model->Type, \common\models\Logins::getTypesArray())) {
                        return \common\models\Logins::getTypesArray()[$model->Type];
                    } else return NULL;
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
