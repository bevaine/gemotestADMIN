<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\GmsGroupDevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Группы устройств';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-group-devices-index">

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'group_id',
            'group_name',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        /** @var \common\models\GmsGroupDevices $model */
                        $customurl = Yii::$app->getUrlManager()->createUrl([
                            'GMS/gms-group-devices/view',
                            'group_id' => $model['group_id']
                        ]);
                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-eye-open"></span>', $customurl,
                            ['title' => Yii::t('yii', 'View'), 'data-pjax' => '0']);
                    },
                    'update' => function ($url, $model) {
                        /** @var \common\models\GmsGroupDevices $model */
                        $customurl = Yii::$app->getUrlManager()->createUrl([
                            'GMS/gms-group-devices/update',
                            'group_id' => $model['group_id']
                        ]);
                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-pencil"></span>', $customurl,
                            ['title' => Yii::t('yii', 'Update'), 'data-pjax' => '0']);
                    },
                    'delete' => function ($url, $model) {
                        /** @var \common\models\GmsGroupDevices $model */
                        $customurl = Yii::$app->getUrlManager()->createUrl([
                            'GMS/gms-group-devices/delete',
                            'group_id' => $model['group_id']
                        ]);
                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-trash"></span>', $customurl,
                            [
                                'title' => Yii::t('yii', 'Delete'),
                                'data-pjax' => '0',
                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'data-method' => 'post',
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>
</div>
