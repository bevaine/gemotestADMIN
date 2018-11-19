<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DoctorSpecSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Запись на прием';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctor-spec-index">
    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'Name',
            'LastName',
            [
                'attribute' => 'SpetialisationID',
                'value' => function($model) {
                    /** @var \common\models\DoctorSpec $model*/
                    return $model->spec->specName;
                }
            ],
            [
                'attribute' => 'Fkey',
                'value' => function($model) {
                    /** @var \common\models\DoctorSpec $model*/
                    return implode(", ", $model->getSenders());
                    //$senders = \common\models\SprFilials::findOne(['Fkey' => $model->Fkey]);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        /** @var \common\models\LoginsSearch $model */
                        $customurl = Yii::$app->getUrlManager()->createUrl([
                            'admin/doctor-spec/view',
                            'GroupID' => $model['GroupID']
                        ]);
                        return Html::a( '<span class="glyphicon glyphicon-eye-open"></span>', $customurl,
                            [
                                'target' => '_blank',
                                'title' => 'Загрузить settings.xml',
                                'data-pjax' => '0'
                            ]
                        );
                    },
                    'update' => function ($url, $model) {
                        /** @var \common\models\LoginsSearch $model */
                        $customurl = Yii::$app->getUrlManager()->createUrl([
                            'admin/doctor-spec/update',
                            'GroupID' => $model['GroupID']
                        ]);
                        return Html::a( '<span class="glyphicon glyphicon-pencil"></span>', $customurl,
                            [
                                'target' => '_blank',
                                'title' => 'Загрузить settings.xml',
                                'data-pjax' => '0'
                            ]
                        );
                    },
                    'delete' => function ($url, $model) {
                        /** @var \common\models\LoginsSearch $model */
                        $customurl = Yii::$app->getUrlManager()->createUrl([
                            'admin/doctor-spec/delete',
                            'GroupID' => $model['GroupID']
                        ]);
                        return Html::a( '<span class="glyphicon glyphicon-trash"></span>', $customurl,
                            [
                                'target' => '_blank',
                                'title' => 'Загрузить settings.xml',
                                'data-pjax' => '0'
                            ]
                        );
                    },
                ],
                'template' => '{view} {update} {delete} '
            ]
        ],
    ]); ?>
</div>
