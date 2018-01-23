<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MedReturnWithoutItem */

$this->title = 'Create Med Return Without Item';
$this->params['breadcrumbs'][] = ['label' => 'Med Return Without Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="med-return-without-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
