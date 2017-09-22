<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Kontragents */

$this->title = 'Редактирование: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Котрагенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->AID]];
$this->params['breadcrumbs'][] = 'Редактрирование';
?>
<div class="kontragents-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
