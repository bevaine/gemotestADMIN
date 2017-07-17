<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\NewsBlog */

$this->title = 'Update News Blog: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'News Blogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="news-blog-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
