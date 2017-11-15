<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\NReturnWithoutItem */

$this->title = 'Create Nreturn Without Item';
$this->params['breadcrumbs'][] = ['label' => 'Nreturn Without Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nreturn-without-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
