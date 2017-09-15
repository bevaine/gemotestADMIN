<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Logins */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$blockDateEnd = true;
$blockRegister = true;

if (empty($model->DateEnd) || strtotime($model->DateEnd) > time()) {
    $blockDateEnd = false;
}
if (empty($model->block_register) || strtotime($model->block_register) > time()) {
    $blockRegister = false;
}

?>

<div class="logins-view">

    <h1><?php //Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->aid], ['class' => 'btn btn-primary']) ?>

        <?= Html::a($blockDateEnd ? 'Включить УЗ' : 'Отключить УЗ',
            [
                'block-account',
                'id' => $model->aid,
                'action' => $blockDateEnd ? 'active' : 'block'
            ],
            ['class' => $blockDateEnd ? 'btn btn-success' : 'btn btn-danger']);
        ?>
        <?= Html::a($blockRegister ? 'Вкл. рег. заказов' : 'Откл. рег. заказов',
            [
                'block-register',
                'id' => $model->aid,
                'action' => $blockRegister ? 'active' : 'block'
            ],
            ['class' => $blockRegister ? 'btn btn-success' : 'btn btn-warning']);
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
                'value' => $model->adUsersOne->last_name
            ],
            [
                'label' => 'Имя AD',
                'value' => $model->adUsersOne->first_name
            ],
            [
                'label' => 'Отчество AD',
                'value' => $model->adUsersOne->middle_name
            ],
            [
                'label' => 'Должность AD',
                'value' => $model->adUsersOne->AD_position
            ],
            [
                'label' => 'Логин AD',
                'value' => $model->adUserAccountsOne->ad_login
            ],
            [
                'label' => 'Пароль AD',
                'value' => $model->adUserAccountsOne->ad_pass
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
