<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SkynetRolesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Роли';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => Url::to(["./logins"])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skynet-roles-index">

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
                'attribute' => 'type',
                'filter' => \common\models\AddUserForm::getTypes(),
                'value' => function ($model) {
                    /** @var \common\models\SkynetRoles $model */
                    if (!is_null($model->type)) {
                        return \common\models\AddUserForm::getTypes($model->type);
                    } else return null;
                }
            ],
            'name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
