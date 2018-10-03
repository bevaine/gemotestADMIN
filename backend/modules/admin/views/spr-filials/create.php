<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\SprFilials */

$this->title = 'Создать отделение';
$this->params['breadcrumbs'][] = ['label' => 'Запись на прием: Отделения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spr-filials-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
