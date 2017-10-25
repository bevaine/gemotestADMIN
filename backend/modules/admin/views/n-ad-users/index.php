<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NAdUsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Nad Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nad-users-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Nad Users', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'ID',
            'last_name',
            'first_name',
            'middle_name',
            'gs_id',
            'gs_key',
            'AD_name',
            'allow_gs',
            'active',
            'AD_login',
            'AD_active',
            'auth_ldap_only',
            //'subdivision',
            //'publicEmployee.q19',
            //'table_number',
            //'publicEmployee.q20',
            //'publicEmployee.q8',
            //'publicEmployee.q9',
            // 'AD_position',
            // 'AD_email:email',
            // 'table_number',
            // 'create_date',
            // 'last_update',
            // 'gs_usertype',
            // 'gs_email:email',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
