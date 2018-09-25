<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model common\models\SkynetRoles */

$this->title = 'Создание ролей пользователей';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => Url::to(["./logins"])];
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skynet-roles-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
