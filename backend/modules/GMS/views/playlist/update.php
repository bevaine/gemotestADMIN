<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GmsPlaylist */

$this->title = 'Редактирование шаблона: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны плейлистов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="gms-playlist-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
