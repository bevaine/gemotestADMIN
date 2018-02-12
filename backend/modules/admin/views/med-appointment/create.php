<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MedAppointment */

$this->title = 'Create Med Appointment';
$this->params['breadcrumbs'][] = ['label' => 'Med Appointments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="med-appointment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
