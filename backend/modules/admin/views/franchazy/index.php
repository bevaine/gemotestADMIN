<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FranchazySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Franchazies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="franchazy-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Franchazy', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'AID',
            'Active',
            'Key',
            'Login',
            'Pass',
            [
                'value' => function($model) {
                    /* @var \common\models\Franchazy $model */
                    return \yii\helpers\StringHelper::truncate($model->Name, 25);
                },
                'attribute' => 'Name'
            ],
            // 'IsOperator',
            // 'Email:email',
            // 'IsAdmin',
             //'BlankText',
            [
                'value' => function($model) {
                    /* @var \common\models\Franchazy $model */
                    return \yii\helpers\StringHelper::truncate($model->BlankName, 25);
                },
                'attribute' => 'BlankName'
            ],
            // 'Logo',
            // 'LogoText',
            // 'LogoText2',
            // 'LogoType',
            // 'LogoWidth',
            // 'TextPaddingLeft',
            // 'OpenExcel',
            // 'EngVersion',
             'InputOrder',
             'PriceID',
            // 'CanRegister',
            // 'InputOrderRM',
            // 'OpenActive',
            // 'ReestrUslug',
             'LCN',
            // 'Li_cOrg',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
