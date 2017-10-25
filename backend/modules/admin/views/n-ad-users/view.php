<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\NAdUsers */

$this->title = $model->gs_id;
$this->params['breadcrumbs'][] = ['label' => 'Nad Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nad-users-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->gs_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->gs_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID',
            'last_name',
            'first_name',
            'middle_name',
            'AD_name',
            'AD_position',
            'AD_email:email',
            'table_number',
            'subdivision',
            'create_date',
            'last_update',
            'gs_id',
            'gs_key',
            'gs_usertype',
            'gs_email:email',
            'allow_gs',
            'active',
            'AD_login',
            'AD_active',
            'auth_ldap_only',
        ],
    ]) ?>

</div>
