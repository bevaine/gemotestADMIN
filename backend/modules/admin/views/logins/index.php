<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use common\models\Logins;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LoginsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
//        'rowOptions' => function ($model) {
//            if (array_key_exists($model['UserType'], \common\models\Logins::getTypesArray())) {
//                return ['style' => 'color:white;background-color:'.Logins::getColorTypes()[$model['UserType']]];
//            } else return NULL;
//        },
        'striped'=>true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'headerOptions' => array('style' => 'width: 75px;'),
                'attribute' => 'aid',
            ],
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
                'label' => 'Пароль GS',
                'value' => 'Pass',
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
                'value' => 'loginAD',
                'headerOptions' => array('style' => 'width: 100px;'),
            ],
            [
                'label' => 'Пароль AD',
                'value' => 'passAD',
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
                'template' => '{view} {update}'
            ]
        ],
    ]); ?>
</div>