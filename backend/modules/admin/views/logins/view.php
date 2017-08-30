<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Logins */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Logins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logins-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->aid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->aid], [
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
            'aid',
            'adUsers.last_name',
            'adUsers.first_name',
            'adUsers.middle_name',
            'adUsers.AD_position',
            'adUserAccounts.ad_login',
            'adUserAccounts.ad_pass',
            'Login',
            'Pass',
            'Name',
            'Email:email',
            'Key',
            'Logo',
            'LogoText',
            'LogoText2',
            'UserType',
            'CACHE_Login',
            'LastLogin',
            [
                'attribute' => 'DateBeg',
                'format' => 'datetime',
            ],
            'DateBeg',
            [
                'attribute' => 'DateEnd',
                'format' => 'html',
                'value' => function($model){
                    if (empty($model->DateEnd)) return NULL;
                    $date = date("d.m.Y G:i:s", strtotime($model->DateEnd));
                    if (empty($model->DateEnd) || strtotime($model->DateEnd) > time()) {
                        $style = "success";
                    } else {
                        $style = "danger";
                    }
                    return Html::tag('span', Html::encode($date), ['class' => 'label label-'.$style]);
                }
            ],
            [
                'attribute' => 'block_register',
                'format' => 'html',
                'value' => function($model){
                    if (empty($model->block_register)) return NULL;
                    $date = date("d.m.Y G:i:s", strtotime($model->block_register));
                    if (empty($model->block_register) || strtotime($model->block_register) > time()) {
                        $style = "success";
                    } else {
                        $style = "danger";
                    }
                    return Html::tag('span', Html::encode($date), ['class' => 'label label-'.$style]);
                }
            ],
            //'InputOrder',
            //'IsOperator',
            //'IsAdmin',
            //'LogoType',
            //'LogoWidth',
            //'TextPaddingLeft',
            //'OpenExcel',
            //'EngVersion',
            //'tbl',
            //'IsDoctor',
            //'PriceID',
            //'CanRegister',
            // 'InputOrderRM',
            //'OrderEdit',
            //'MedReg',
            //'goscontract',
            //'FizType',
            //'clientmen',
            //'mto',
            //'mto_editor',
            //'last_update_password',
            //'show_preanalytic',
            //'role',
            //'parentAid',
            //'GarantLetter',
        ],
    ]) ?>

</div>
