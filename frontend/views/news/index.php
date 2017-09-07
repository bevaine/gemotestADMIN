<?php

use yii\helpers\Html;
use yii\grid\GridView;
use nill\widgetapn\Widgetapn;
use yii\bootstrap\Carousel;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Новостной Блог';
$this->params['breadcrumbs'][] = $this->title;

$template = '{view}';
if (!Yii::$app->user->isGuest) $template .= "{update}";
?>

<div class="news-blog-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => "user_id",
                'value' => function($model) { return $model->user->username; },
                'header' => "Имя"
            ],
            'title',
            'publiched_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => $template
            ],
        ],
    ]);
    ?>
</div>
