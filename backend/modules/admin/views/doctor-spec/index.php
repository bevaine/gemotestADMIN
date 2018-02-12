<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DoctorSpecSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Doctor Specs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctor-spec-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Doctor Spec', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'AID',
            'Name',
            'LastName',
            'SpetialisationID',
            'Active',
            'GroupID',
            'Fkey',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
