<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Logins */

$this->title = 'Создание пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logins-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
