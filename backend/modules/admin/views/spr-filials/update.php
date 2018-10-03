<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SprFilials */

$this->title = 'Редактирование: ' . $model->AID;
$this->params['breadcrumbs'][] = ['label' => 'Запись на прием: Отделения', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->AID, 'url' => ['view', 'id' => $model->AID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="spr-filials-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
