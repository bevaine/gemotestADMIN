<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LoginsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//print_r($dataProvider);

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="logins-index">

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'striped'=>true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'headerOptions' => array('style' => 'width: 75px;'),
                'attribute' => 'aid',
            ],
//            [
//                'label' => 'Фамилия',
//                'name' => 'last_name',
//                'attribute' => 'Logins.last_name',
//                'value' => 'last_name',
//                'headerOptions' => array('style' => 'width: 120px;'),
//            ],
            [
                'headerOptions' => array('style' => 'width: 75px;'),
                'attribute' => 'Key',
            ],
            [
                'headerOptions' => array('style' => 'width: 75px;'),
                'attribute' => 'UserType',
                'filter' => \common\models\Logins::getTypesArray(),
                'format' => 'text',
                'value' => function ($model) {
                    return \common\models\Logins::getTypesArray()[$model->UserType];
                }
            ],
            [
                'attribute' => 'Name',
                'value' => function ($model) {
                    return strlen($model->Name) > 65 ? substr($model->Name,0, 65 )."..." : $model->Name;
                }
            ],
            [
                'attribute'=>'Login',
                'headerOptions' => array('style' => 'width: 100px;'),
            ],
            [
                'label' => 'Пароль GS',
                'value' => 'Pass',
            ],
            [
                'label' => 'Фамилия',
                'attribute' => 'last_name',
                'value' => 'adUsersNew.last_name',
                'headerOptions' => array('style' => 'width: 120px;'),
            ],
            [
                'label' => 'Имя',
                'attribute' => 'first_name',
                'value' => 'adUsersNew.first_name',
                'headerOptions' => array('style' => 'width: 120px;'),
            ],
            [
                'label' => 'Отчество',
                'attribute' => 'middle_name',
                'value' => 'adUsersNew.middle_name',
                'headerOptions' => array('style' => 'width: 120px;'),
            ],
            [
                'label' => 'Должность',
                'attribute' => 'AD_position',
                'value' => function ($model) {
                    /** @var \common\models\LoginsSearch $model */
                    $AD_position = $model->adUsersNew->AD_position;
                    return strlen($AD_position) > 35 ? substr($AD_position, 0, 35) . "..." : $AD_position;
                },
                'headerOptions' => array('style' => 'width: 100px;'),
            ],
            [
                'label' => 'Логин AD',
                'attribute' => 'ad_login',
                'value' => 'adUsersNew.adUserAccounts.ad_login',
                'headerOptions' => array('style' => 'width: 100px;'),
            ],
            [
                'label' => 'Пароль AD',
                'value' => 'adUsersNew.adUserAccounts.ad_pass',
                'headerOptions' => array('style' => 'width: 100px;'),
            ],
            [
                'label' => 'Фамилия',
                'headerOptions' => array('style' => 'width: 120px;'),
                'attribute'=>'last_name',
                'value' => function ($model) use ($searchModel) {
                    /** @var \common\models\LoginsSearch $model */
                    $findName = $model->getAdUsers()
                        ->andFilterWhere(['like', 'last_name', $searchModel->last_name])
                        ->andFilterWhere(['like', 'first_name', $searchModel->first_name])
                        ->andFilterWhere(['like', 'middle_name', $searchModel->middle_name])
                        ->andFilterWhere(['like', 'AD_position', $searchModel->AD_position]);
                    if ($findName->count() == 1) {
                        return $findName->one()->last_name;
                    } elseif ($findName->count() > 1) {
                        return Html::tag('span', Html::encode('(несколько)'),
                            ['style' => 'font-style: italic; color: red']);
                    } else return null;
                },
                'format' => 'html',
            ],
//            [
//                'label' => 'Имя',
//                'headerOptions' => array('style' => 'width: 120px;'),
//                'attribute'=>'first_name',
//                'value' => function ($model) use ($searchModel) {
//                    /** @var \common\models\LoginsSearch $model */
//                    $findName = $model->getAdUsers()
//                        ->andFilterWhere(['like', 'last_name', $searchModel->last_name])
//                        ->andFilterWhere(['like', 'first_name', $searchModel->first_name])
//                        ->andFilterWhere(['like', 'middle_name', $searchModel->middle_name])
//                        ->andFilterWhere(['like', 'AD_position', $searchModel->AD_position]);
//                    if ($findName->count() == 1) {
//                        return $findName->one()->first_name;
//                    } elseif ($findName->count() > 1) {
//                        return Html::tag('span', Html::encode('(несколько)'),
//                            ['style' => 'font-style: italic; color: red']);
//                    } else return null;
//                },
//                'format' => 'html',
//            ],
//            [
//                'label' => 'Отчество1',
//                'attribute' => 'adUsersNew.last_name'
//            ],
//            [
//                'label' => 'Отчество',
//                'headerOptions' => array('style' => 'width: 120px;'),
//                'attribute'=>'middle_name',
//                'value' => function ($model) use ($searchModel) {
//                    /** @var \common\models\LoginsSearch $model */
//                    $findName = $model->getAdUsers()
//                        ->andFilterWhere(['like', 'last_name', $searchModel->last_name])
//                        ->andFilterWhere(['like', 'first_name', $searchModel->first_name])
//                        ->andFilterWhere(['like', 'middle_name', $searchModel->middle_name])
//                        ->andFilterWhere(['like', 'AD_position', $searchModel->AD_position]);
//                    if ($findName->count() == 1) {
//                        return $findName->one()->middle_name;
//                    } elseif ($findName->count() > 1) {
//                        return Html::tag('span', Html::encode('(несколько)'),
//                        ['style' => 'font-style: italic; color: red']);
//                    } else return null;
//                },
//                'format' => 'html',
//            ],
//            [
//                'label' => 'Должность',
//                'headerOptions' => array('style' => 'width: 100px;'),
//                'attribute'=>'AD_position',
//                'value' => function ($model) use ($searchModel) {
//                    /** @var \common\models\LoginsSearch $model */
//                    $findName = $model->getAdUsers()
//                        ->andFilterWhere(['like', 'last_name', $searchModel->last_name])
//                        ->andFilterWhere(['like', 'first_name', $searchModel->first_name])
//                        ->andFilterWhere(['like', 'middle_name', $searchModel->middle_name])
//                        ->andFilterWhere(['like', 'AD_position', $searchModel->AD_position]);
//                    if ($findName->count() == 1) {
//                        $AD_position = $findName->one()->AD_position;
//                        return strlen($AD_position) > 35 ? substr($AD_position, 0, 35) . "..." : $AD_position;
//                    } elseif ($findName->count() > 1) return Html::tag('span', Html::encode('(несколько)'),
//                            ['style' => 'font-style: italic; color: red']
//                        );
//                    else return null;
//                },
//                'format' => 'html',
//            ],
//
//            [
//                'label' => 'Логин AD',
//                'headerOptions' => array('style' => 'width: 100px;'),
//                'attribute'=>'ad_login',
//                'value' => function ($model) use ($searchModel) {
//                    /** @var \common\models\LoginsSearch $model */
//                    $findName = $model->getAdUsers()
//                        ->andFilterWhere(['like', 'last_name', $searchModel->last_name])
//                        ->andFilterWhere(['like', 'first_name', $searchModel->first_name])
//                        ->andFilterWhere(['like', 'middle_name', $searchModel->middle_name])
//                        ->andFilterWhere(['like', 'AD_position', $searchModel->AD_position]);
//                    if ($findName->count() == 1) {
//                        return $findName->one()->adUserAccounts->ad_login;
//                    } elseif ($findName->count() > 1) {
//                        return Html::tag('span', Html::encode('(несколько)'),
//                            ['style' => 'font-style: italic; color: red']);
//                    } else return null;
//                },
//                'format' => 'html',
//            ],
//            [
//                'label' => 'Пароль AD',
//                'headerOptions' => array('style' => 'width: 100px;'),
//                'attribute'=>'ad_pass',
//                'value' => function ($model) use ($searchModel) {
//                    /** @var \common\models\LoginsSearch $model */
//                    $findName = $model->getAdUsers()
//                        ->andFilterWhere(['like', 'last_name', $searchModel->last_name])
//                        ->andFilterWhere(['like', 'first_name', $searchModel->first_name])
//                        ->andFilterWhere(['like', 'middle_name', $searchModel->middle_name])
//                        ->andFilterWhere(['like', 'AD_position', $searchModel->AD_position]);
//                    if ($findName->count() == 1) {
//                        return $findName->one()->adUserAccounts->ad_pass;
//                    } elseif ($findName->count() > 1) {
//                        return Html::tag('span', Html::encode('(несколько)'),
//                            ['style' => 'font-style: italic; color: red']);
//                    } else return null;
//                },
//                'format' => 'html',
//            ],
            //'CACHE_Login',
            [
                'label' => 'Доступ к УЗ',
                'headerOptions' => array('style' => 'width: 100px; text-align: center;'),
                'filter' => \common\models\Logins::getStatusesArray(),
                'attribute' => 'DateEnd',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    /** @var \common\models\LoginsSearch $model */
                    /** @var \yii\grid\DataColumn $column */
                    $value = $model->{$column->attribute};
                    if (empty($value) || strtotime($value) > time()) {
                        $name = 'Активен';
                        $class = 'success';
                    } else {
                        $name = 'Заблокирован';
                        $class = 'danger';
                    }
                    $html = Html::tag('span', Html::encode($name), ['class' => 'label label-' . $class]);
                    return $html;
                }
            ],
            [
                'label' => 'Доступ к рег.',
                'headerOptions' => array('style' => 'width: 100px; align: center;'),
                'filter' => \common\models\Logins::getStatusesArray(),
                'attribute' => 'block_register',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    /** @var \common\models\LoginsSearch $model */
                    /** @var \yii\grid\DataColumn $column */
                    $value = $model->{$column->attribute};
                    if (empty($value) || strtotime($value) > time()) {
                        $name = 'Активен';
                        $class = 'success';
                    } else {
                        $name = 'Заблокирован';
                        $class = 'danger';
                    }
                    $html = Html::tag('span', Html::encode($name), ['class' => 'label label-' . $class]);
                    return $html;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) use ($searchModel) {
                        /** @var \common\models\LoginsSearch $model */
                        $arrReturn = ['admin/logins/view', 'id' => $model['aid']];
                        $findName = $model->getAdUsers()
                            ->andFilterWhere(['like', 'last_name', $searchModel->last_name])
                            ->andFilterWhere(['like', 'first_name', $searchModel->first_name])
                            ->andFilterWhere(['like', 'middle_name', $searchModel->middle_name]);

                        if ($findName->count() == 1) {
                            if (!empty($findName->one()->last_name))
                                $arrReturn = array_merge($arrReturn, ['last_name' => $findName->one()->last_name]);
                            if (!empty($findName->one()->first_name))
                                $arrReturn = array_merge($arrReturn, ['first_name' => $findName->one()->first_name]);
                            if (!empty($findName->one()->middle_name))
                                $arrReturn = array_merge($arrReturn, ['middle_name' => $findName->one()->middle_name]);
                        }

                        $customurl = Yii::$app->getUrlManager()->createUrl($arrReturn);

                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-eye-open"></span>', $customurl,
                            ['title' => Yii::t('yii', 'View'), 'data-pjax' => '0']);
                    }
                ],
                'template' => '{view} {update}'
            ]
        ],
    ]); ?>
</div>

