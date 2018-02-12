<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\DoctorSpec */

$this->title = 'Create Doctor Spec';
$this->params['breadcrumbs'][] = ['label' => 'Doctor Specs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctor-spec-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
