<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\NKkmUsers */

$this->title = 'Create Nkkm Users';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи ККМ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nkkm-users-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
