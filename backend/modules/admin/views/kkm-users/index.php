<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NKkmUsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи ККМ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nkkm-users-index">
    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a( '<i class="glyphicon glyphicon-envelope" aria-hidden="true"></i> Загрузить сертификат',
                ['/upload/ca.p7b'],
                ['class' => 'btn btn-primary',
                    'target' => '_blank',
                    'title' => 'Загрузить',
                    'data-pjax' => '0'
                ]
            );
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'kkm_id',
            //'user_type',
            'user_id',
            [
                'attribute' => 'name_gs',
                'value' => 'logins.Name'
            ],
            [
                'attribute' => 'fio',
                'format' => 'text',
                'value' => function ($model) {
                    /** @var \common\models\NKkmUsers $model */
                    if (!empty($model->logins->operators->fio)) {
                        $fio = $model->logins->operators->fio;
                        return !empty($fio) ? $fio : $model->logins->Name;
                    } else return $model->logins->Name;
                },
            ],
            [
                'attribute' => 'number',
                'value' => 'kkm.number'
            ],
            [
                'filter' =>  \common\models\NKkmUsers::getSenderList(),
                'value' => function ($model) {
                    /** @var $model \common\models\NKkmUsers */
                    return !empty($model->kkm) ? $model->kkm->sender_key : null;
                },
                'attribute' => 'sender_key'
            ],
            'login',
            'password',
            //'logins.operators.LastName',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'export' => function ($url, $model) {
                        /** @var \common\models\LoginsSearch $model */
                        $customurl = Yii::$app->getUrlManager()->createUrl([
                            'admin/kkm-users/export',
                            'id' => $model['id']
                        ]);
                        return Html::a( '<span class="glyphicon glyphicon-save"></span>', $customurl,
                            [
                                'target' => '_blank',
                                'title' => 'Загрузить settings.xml',
                                'data-pjax' => '0'
                            ]
                        );
                    },
                ],
                'template' => '{export} {view} {update} {delete} '
            ]
        ],
    ]); ?>
</div>
