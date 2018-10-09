<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Franchazy */

$this->title = 'Создание франчайзи';
$this->params['breadcrumbs'][] = ['label' => 'Франчайзи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="franchazy-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
