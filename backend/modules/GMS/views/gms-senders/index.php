<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;
use yii\web\JsExpression;
use common\components\helpers\FunctionsHelper;


/* @var $this yii\web\View */
/* @var $searchModel common\models\GmsSendersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отделения';
$this->params['breadcrumbs'][] = $this->title;
$url = \yii\helpers\Url::to(['/admin/kontragents/ajax-kontragents-list']);

?>
<div class="gms-senders-index">

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
                'label' => 'Отделение',
                'filter' => Select2::widget([
                    'name' => 'GmsSendersSearch[sender_key]',
                    'value' => $searchModel->sender_key,
                    'options' => ['placeholder' => 'Наименование отделения'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 2,
                        'multiple' => false,
                        'ajax' => [
                            'url' => $url,
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return { search:params.term}; }'),
                            'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
                            'cache' => true
                        ],
                        'initSelection' => new JsExpression(FunctionsHelper::AjaxInitScript($url)),
                    ],
                ]),
                'value' => function ($model) {
                    /** @var \common\models\GmsSenders $model  */
                    return !empty($model->sender_name) ? $model->sender_name : null;
                },

                'attribute' => 'sender_key',
                'format' => 'raw'
            ],
            [
                'filter' =>  \common\models\GmsRegions::getRegionList(),
                'label' => 'Регион',
                'value' => function ($model) {
                    /** @var \common\models\GmsSenders $model */
                    return \common\models\GmsRegions::findOne($model->region_id)->region_name;
                },
                'attribute' => 'region_id',
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
