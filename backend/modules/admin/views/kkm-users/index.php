<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NKkmUsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Nkkm Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nkkm-users-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Nkkm Users', ['create'], ['class' => 'btn btn-success']) ?>
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
            'logins.Name',
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
            'kkm.number',
            'kkm.sender_key',
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
                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-save"></span>', $customurl,
                            [
                                'target' => '_blank',
                                'title' => Yii::t('yii', 'Export'),
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
