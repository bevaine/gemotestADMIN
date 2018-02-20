<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GmsRegions */

$this->title = 'Создать Регионы';
$this->params['breadcrumbs'][] = ['label' => 'Регионы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-regions-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
