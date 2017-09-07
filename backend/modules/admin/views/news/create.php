<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\NewsBlog */

$this->title = 'Create News Blog';
$this->params['breadcrumbs'][] = ['label' => 'News Blogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-blog-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
