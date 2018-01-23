<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MedReturnWithoutItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Возврат МИС (без номенклатуры)';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="med-return-without-item-index">
    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'user_aid',
                'value' => function($model) {
                    /** @var \common\models\LoginsSearch $model */
                    return Html::a(
                        $model->user_aid,
                        '/admin/logins/view?id='.$model->user_aid,
                        [
                            'title' => $model->user_aid,
                            'target' => '_blank'
                        ]
                    );
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'parent_id',
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var \common\models\LoginsSearch $model */
                    if (!isset($model->parent_id)) return null;
                    $customurl = Yii::$app->getUrlManager()->createUrl([
                        'admin/med-return-order/view',
                        'id' => $model->parent_id,
                    ]);
                    return Html::a(
                        $model->parent_id,
                        $customurl,
                        [
                            'title' => $model->parent_id,
                            'target' => '_blank'
                        ]
                    );
                },
            ],
            [
                'attribute' => 'order_num',
                'value' => function($data){
                    return Html::a(
                        $data->order_num,
                        'https://office.gemotest.ru/mis/registry/order?order_id='.$data->order_num,
                        [
                            'title' => $data->order_num,
                            'target' => '_blank'
                        ]
                    );
                },
                'format' => 'raw',
            ],
            'total',
            'date',
            [
                'attribute' => 'pay_type',
                'filter' => \common\models\NPay::getPayTypeArray(),
                'value' => function ($model) {
                    return  \common\models\NPay::getPayTypeArray($model['pay_type']);
                }
            ],
            'kkm',
            'z_num',
            // 'comment:ntext',
            // 'path_file',
            // 'base',
            // 'code_1c',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
