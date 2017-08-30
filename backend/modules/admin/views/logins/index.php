<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LoginsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Logins';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logins-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'aid',
            //'Key',
            //'CACHE_Login',
            //'Name',
            [
                'label' => 'Фамилия',
                'attribute'=>'last_name',
                'value' => 'adUsers.last_name',
                'format' => 'text',
            ],
            [
                'label' => 'Имя',
                'attribute'=>'first_name',
                'value' => 'adUsers.first_name',
                'format' => 'text',
            ],
            [
                'label' => 'Отчество',
                'attribute'=>'middle_name',
                'value' => 'adUsers.middle_name',
                'format' => 'text',
            ],
            [
                'label' => 'Должность',
                'headerOptions' => array('style' => 'width: 200px;'),
                'attribute'=>'AD_position',
                'value' => 'adUsers.AD_position',
                'format' => 'text',
            ],
            'Login',
            'Pass',
            [
                'label' => 'Логин AD',
                'headerOptions' => array('style' => 'width: 120px;'),
                'attribute'=>'ad_login',
                'value' => 'adUserAccounts.ad_login',
                'format' => 'text',
            ],
            [
                'label' => 'Пароль AD',
                'headerOptions' => array('style' => 'width: 120px;'),
                'attribute'=>'ad_pass',
                'value' => 'adUserAccounts.ad_pass',
                'format' => 'text',
            ],
            [
                'label' => 'Доступ к УЗ',
                'headerOptions' => array('style' => 'width: 120px; text-align: center;'),
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
                'headerOptions' => array('style' => 'width: 120px; align: center;'),
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
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
