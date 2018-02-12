<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MedAppointment */

$this->title = 'Update Med Appointment: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Med Appointments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="med-appointment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
