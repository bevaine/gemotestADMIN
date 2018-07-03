<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\NKkmUsers */

$this->title = 'Редактирование пользователя: '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи ККМ', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="nkkm-users-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
