<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GmsPlaylistOut */

$this->title = 'Update Gms Playlist Out: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Gms Playlist Outs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="gms-playlist-out-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
