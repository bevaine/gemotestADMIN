<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LoginsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => Url::to(["./logins"])
];

$gridColumns = [
    [
        'label' => 'Отделение',
        'value' => function ($model) {
            return str_replace('&quot;', '"', $model['Name']);
        }
    ],
    [
        'label' => 'Фамилия',
        'value' => 'last_name'
    ],
    [
        'label' => 'Имя',
        'value' => 'first_name'
    ],
    [
        'label' => 'Отчество',
        'value' => 'middle_name'
    ],
    [
        'label' => 'Должность',
        'value' => 'AD_position'
    ],
    [
        'label' => 'Логин',
        'value' => 'AD_login'
    ],
    [
        'label' => 'Пароль',
        'value' => 'passAD'
    ],
];
?>
<div class="logins-index">

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <p>
        <?php
        echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
            //'encoding' => 'windows-1251',
            'target' => ExportMenu::TARGET_POPUP,
            'showConfirmAlert'=>true,
            'timeout' => 1000,
            'exportConfig' => [
//                ExportMenu::FORMAT_CSV => true,
//                ExportMenu::FORMAT_HTML=> true,
//                ExportMenu::FORMAT_TEXT => true,
//                ExportMenu::FORMAT_PDF => false,
                ExportMenu::FORMAT_EXCEL => false,
                ExportMenu::FORMAT_EXCEL_X => false,
            ],
        ]);
        ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
//        'rowOptions' => function ($model) {
//            if (array_key_exists($model['UserType'], \common\models\Logins::getTypesArray())) {
//                return ['style' => 'color:white;background-color:'.Logins::getColorTypes()[$model['UserType']]];
//            } else return NULL;
//        },
        'striped' => true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'headerOptions' => array('style' => 'width: 75px;'),
                'attribute' => 'aid',
                'value' => function($model) {
                    /** @var \common\models\LoginsSearch $model */
                    return Html::a(
                        $model['aid'],
                        'https://office.gemotest.ru/administrator/index.php?r=auth/assignment/view&id='.$model['aid'],
                        [
                            'title' => $model['aid'],
                            'target' => '_blank'
                        ]
                    );
                },
                'format' => 'raw',
            ],
            [
                'headerOptions' => array('style' => 'width: 75px;'),
                'attribute' => 'Key',
                'format' => 'text',
                'value' => function ($model) {
                    /** @var \common\models\LoginsSearch $model */
                    if (array_key_exists($model['UserType'], \common\models\Logins::getTypesArray())) {
                        if ($model['UserType'] == 9 && !empty($model['directorKey'])) {
                            return $model['Key'].' ('.$model['directorKey'].')';
                        } else {
                            return $model['Key'];
                        }
                    } else return NULL;
                },
            ],
            [
                'headerOptions' => array('style' => 'width: 75px;'),
                'attribute' => 'UserType',
                'filter' => \common\models\Logins::getTypesArray(),
                'format' => 'text',
                'value' => function ($model) {
                    /** @var \common\models\LoginsSearch $model */
                    if (array_key_exists($model['UserType'], \common\models\Logins::getTypesArray())) {
                        return \common\models\Logins::getTypesArray()[$model['UserType']];
                    } else return NULL;
                }
            ],
            [
                'attribute' => 'Name',
                'value' => function ($model) {
                    return strlen($model['Name']) > 65 ? substr($model['Name'],0, 65 )."..." : $model['Name'];
                }
            ],
            [
                'attribute'=>'Login',
                'headerOptions' => array('style' => 'width: 100px;'),
            ],
            [
                'label' => 'Фамилия AD',
                'attribute' => 'last_name',
                'headerOptions' => array('style' => 'width: 120px;'),
            ],
            [
                'label' => 'Имя AD',
                'attribute' => 'first_name',
                'value' => 'first_name',
                'headerOptions' => array('style' => 'width: 120px;'),
            ],
            [
                'label' => 'Отчество AD',
                'attribute' => 'middle_name',
                'value' => 'middle_name',
                'headerOptions' => array('style' => 'width: 120px;'),
            ],
            [
                'label' => 'Должность AD',
                'attribute' => 'AD_position',
                'value' => function ($model) {
                    /** @var \common\models\LoginsSearch $model */
                    if (!empty($model['AD_position'])) {
                        $AD_position = $model['AD_position'];
                        return strlen($AD_position) > 35 ? substr($AD_position, 0, 35) . "..." : $AD_position;
                    } else return NULL;
                },
                'headerOptions' => array('style' => 'width: 100px;'),
            ],
            [
                'label' => 'Логин AD',
                'attribute' => 'ad_login',
                'value' => function ($model) {
                    /** @var \common\models\LoginsSearch $model */
                    return !empty($model["AD_login"]) ? 'lab\\'.$model["AD_login"] : null;
                },
                'headerOptions' => array('style' => 'width: 100px;'),
            ],
            [
                'label' => 'Доступ к УЗ',
                'headerOptions' => array('style' => 'width: 100px; text-align: center;'),
                'filter' => \common\models\Logins::getStatusesArray(),
                'attribute' => 'DateEnd',
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var \common\models\LoginsSearch $model */
                    $value = $model['DateEnd'];
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
                'value' => function ($model) {
                    /** @var \common\models\LoginsSearch $model */
                    $value = $model['block_register'];
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
                    'view' => function ($url, $model) {
                        /** @var \common\models\LoginsSearch $model */
                        $customurl = Yii::$app->getUrlManager()->createUrl([
                            'admin/logins/view',
                            'id' => $model['aid'],
                            'ad' => $model['ID']
                        ]);
                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-eye-open"></span>', $customurl,
                            ['title' => Yii::t('yii', 'View'), 'data-pjax' => '0']);
                    },
                    'update' => function ($url, $model) {
                        /** @var \common\models\LoginsSearch $model */
                        $customurl = Yii::$app->getUrlManager()->createUrl([
                            'admin/logins/update',
                            'id' => $model['aid'],
                            'ad' => $model['ID']
                        ]);
                        return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-pencil"></span>', $customurl,
                            ['title' => Yii::t('yii', 'View'), 'data-pjax' => '0']);
                    },

                ],
                'template' => '{view} {update}'
            ]
        ],
    ]); ?>
</div>