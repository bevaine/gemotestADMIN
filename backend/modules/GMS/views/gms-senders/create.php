<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\GmsSenders */

$this->title = 'Создать отделение';
$this->params['breadcrumbs'][] = ['label' => 'Отделения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gms-senders-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
