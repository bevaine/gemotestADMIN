<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RepPeriodLabGemotestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мед. сообщество';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rep-period-lab-gemotest-index">

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'contract',
            'sender_id',
            'login',
            'date_start',
            'date_end',
            //'pass',
            //'date_active',
            //'reward',
            //'test_period',
            //'deleted',
            //'user_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
