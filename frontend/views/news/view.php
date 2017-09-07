<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\NewsBlog */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'News Blogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-blog-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?
        if (!Yii::$app->user->isGuest) {
           echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'title',
            'content',
            'publiched_at',
        ],
    ]) ?>

</div>
