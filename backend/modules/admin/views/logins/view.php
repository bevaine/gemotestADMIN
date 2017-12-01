<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $ad integer */
/* @var $model common\models\Logins */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$display = '';
$blockDateEnd = 'active';
$blockRegister = 'active';
$activeGS = 'active';

if (empty($model->DateEnd) || strtotime($model->DateEnd) > time()) {
    $blockDateEnd = 'block';
}
if (empty($model->block_register) || strtotime($model->block_register) > time()) {
    $blockRegister = 'block';
}
if ($model->adUsers) {
    if ($model->adUsers->auth_ldap_only == 1) {
        $activeGS = 'block';
    }
} else {
    $display = 'none;';
}
?>


<div class="logins-view">

    <h1><?php //Html::encode($this->title) ?></h1>

    <p>
        <?php $form = ActiveForm::begin(['id'=>'form-input','method' => 'post']); ?>

        <?= Html::a('Редактировать', ['update', 'id' => $model->aid, 'ad' => $ad], ['class' => 'btn btn-primary']) ?>

        <?= Html::SubmitButton($blockDateEnd == 'active' ? 'Включить УЗ' : 'Отключить УЗ', [
            'name' => 'block-account',
            'class' => $blockDateEnd == 'active' ? 'btn btn-success' : 'btn btn-danger',
            'value' => $blockDateEnd
        ]) ?>

        <?= Html::SubmitButton($blockRegister == 'active' ? 'Вкл. рег. заказов' : 'Откл. рег. заказов', [
            'name' => 'block-register',
            'class' => $blockRegister == 'active' ? 'btn btn-success' : 'btn btn-warning',
            'value' => $blockRegister
        ]) ?>

        <?= Html::SubmitButton($activeGS == 'active' ? 'Откл. авториз. GS' : 'Вкл. авториз. GS', [
            'name' => 'active-gs',
            'class' => $activeGS == 'active' ? 'btn btn-success' : 'btn btn-info',
            'value' => $activeGS,
            'style' => 'display: '.$display
        ]) ?>


    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Авторизация через',
                'attribute' => 'adUsers.auth_ldap_only',
                'format' => 'raw',
                'value' => function ($model) {
                    /** @var \common\models\Logins $model  */
                    if ($model->adUsers) {
                        if ($model->adUsers->auth_ldap_only == 0) {
                            return '<span class="text-success"><b>GemoSystem</b></span>';
                        } elseif ($model->adUsers->auth_ldap_only == 1) {
                            return '<span class="text-info"><b>Active Directory</b></span>';
                        }
                    }
                    return '<span class="text-danger"><b>Связь с AD отсутствует</b></span>';
                },
                'options' => ['class' => 'table table-striped table-bordered detail-view']
            ],
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
                'label' => 'Логин AD',
                'value' => !empty($model->adUsers->AD_login) ? 'lab\\'.$model->adUsers->AD_login : null
            ],

            [
                'label' => 'Пароль AD',
                'attribute' => 'adUserAccounts.ad_pass',
            ],            [
                'label' => 'Фамилия AD',
                'attribute' => 'adUsers.last_name',
            ],
            [
                'label' => 'Имя AD',
                'attribute' => 'adUsers.first_name',
            ],
            [
                'label' => 'Отчество AD',
                'attribute' => 'adUsers.middle_name',
            ],
            [
                'label' => 'Должность AD',
                'attribute' => 'adUsers.AD_position',
            ],
            [
                'label' => 'Почта (директор)',
                'attribute' => 'directorInfo.email',
                'visible' => $model->UserType == 9 ? true : false
            ],
            [
                'label' => 'Моб. номер (директор)',
                'attribute' => 'directorInfo.phoneNumber',
                'visible' => $model->UserType == 9 ? true : false
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

    <?php ActiveForm::end(); ?>

</div>

<!--<script>-->
<!--    $(function(){-->
<!--        $("a.poster").click(function() { // все ссылки с классом .poster-->
<!--            var value = $(this).attr("value"); // поле value-->
<!--            var href = $(this).attr("href"); // поле href - адрес, куда посылать-->
<!--            $.POST( href, {value: value}, function(data) { // посылаем-->
<!--                alert( data );-->
<!--            });-->
<!--            return false;-->
<!--        });-->
<!--    });-->
<!--</script>-->
