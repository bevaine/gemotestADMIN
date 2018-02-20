<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GmsPlaylist */

$this->title = 'Создать шаблон';
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны плейлистов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-playlist-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
