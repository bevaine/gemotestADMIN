<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GmsVideos */

$this->title = 'Update Gms Videos: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Gms Videos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="gms-videos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
