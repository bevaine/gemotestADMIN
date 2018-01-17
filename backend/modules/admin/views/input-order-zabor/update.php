<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\InputOrderZabor */

$this->title = 'Update Input Order Iskl Issl Mszabor: ' . $model->aid;
$this->params['breadcrumbs'][] = ['label' => 'Взятие биоматериала', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->aid, 'url' => ['view', 'id' => $model->aid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="input-order-iskl-issl-mszabor-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
