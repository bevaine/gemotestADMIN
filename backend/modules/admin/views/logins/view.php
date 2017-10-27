<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $ad integer */
/* @var $model common\models\Logins */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$display = '';
$blockDateEnd = true;
$blockRegister = true;
$activeGS = true;

if (empty($model->DateEnd) || strtotime($model->DateEnd) > time()) {
    $blockDateEnd = false;
}
if (empty($model->block_register) || strtotime($model->block_register) > time()) {
    $blockRegister = false;
}
if (!empty($ad)) {
    if ($model->adUsersOne->auth_ldap_only == 1) {
        $activeGS = false;
    }
} else {
    $display = 'none;';
}
?>


<div class="logins-view">

    <h1><?php //Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->aid], ['class' => 'btn btn-primary']) ?>

        <?= Html::a($blockDateEnd ? 'Включить УЗ' : 'Отключить УЗ',
            [
                'id' => $model->aid,
                'ad' => $ad,
                'action' => 'block-account',
                'status' => $blockDateEnd ? 'active' : 'block'
            ],
            ['class' => $blockDateEnd ? 'btn btn-success' : 'btn btn-danger']);
        ?>
        <?= Html::a($blockRegister ? 'Вкл. рег. заказов' : 'Откл. рег. заказов',
            [
                'id' => $model->aid,
                'ad' => $ad,
                'action' => 'block-register',
                'status' => $blockRegister ? 'active' : 'block'
            ],
            ['class' => $blockRegister ? 'btn btn-success' : 'btn btn-warning']);
        ?>
        <?= Html::a($activeGS ? 'Откл. авториз. GS' : 'Вкл. авториз. GS',
            [
                'id' => $model->aid,
                'ad' => $ad,
                'action' => 'active-gs',
                'status' => $activeGS ? 'block' : 'active' ,
            ],
            [
                'class' => $activeGS ? 'btn btn-success' : 'btn btn-danger',
                'style' => 'display: '.$display
            ]);
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'aid',
            'Name',
            'Key',
            [
                'attribute' => 'UserType',
                'value' => function ($model) {
                    /** @var \common\models\Logins $model  */
                    if (array_key_exists($model->UserType, \common\models\Logins::getTypesArray())) {
                        return \common\models\Logins::getTypesArray()[$model->UserType];
                    } else return $model->UserType;
                }
            ],
            'Login',
            'Pass',
            [
                'label' => 'Фамилия AD',
                'attribute' => 'adUsersOne.last_name',
            ],
            [
                'label' => 'Имя AD',
                'attribute' => 'adUsersOne.first_name',
            ],
            [
                'label' => 'Отчество AD',
                'attribute' => 'adUsersOne.middle_name',
            ],
            [
                'label' => 'Должность AD',
                'attribute' => 'adUsersOne.AD_position',
            ],
            [
                'label' => 'Логин AD',
                'attribute' => 'adUserAccountsOne.ad_login',
            ],
            [
                'label' => 'Пароль AD',
                'attribute' => 'adUserAccountsOne.ad_pass',
            ],
            'Email:email',
            'Logo',
            'LogoText',
            'LogoText2',
            'CACHE_Login',
            'LastLogin',
            [
                'attribute' => 'DateBeg',
                'format' => 'datetime',
            ],
            [
                'attribute' => 'DateEnd',
                'format' => 'html',
                'value' => function($model){
                    /** @var $model \common\models\Logins */
                    if (empty($model->DateEnd)) return NULL;
                    $date = Yii::$app->formatter->asDatetime(substr($model->DateEnd, 0,-4), 'full');
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
                    /** @var $model \common\models\Logins */
                    if (empty($model->block_register)) return NULL;
                    $date = Yii::$app->formatter->asDatetime(substr($model->block_register, 0,-4), 'full');
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
            //'InputOrderRM',
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
