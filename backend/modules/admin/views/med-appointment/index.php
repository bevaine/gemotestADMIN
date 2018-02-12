<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MedAppointmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Med Appointments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="med-appointment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Med Appointment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'order_id',
            'date',
            'patient_id',
            'user_id',
            'office_id',
            'doctor_id',
            'doctor_guid',
            'nurse_id',
            'nurse_guid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
