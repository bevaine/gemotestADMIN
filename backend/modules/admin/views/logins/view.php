<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $ad integer */
/* @var $model common\models\Logins */

$this->title = 'Просмотр';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$display = '';
$blockDateEnd = 'active';
$blockRegister = 'active';
$activeGS = 'active';

if (empty($model->DateEnd)
    || strtotime($model->DateEnd) > time()) {
    $blockDateEnd = 'block';
}
if (empty($model->block_register)
    || strtotime($model->block_register) > time()) {
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

    <?php $form = ActiveForm::begin(['id'=>'form-input','method' => 'post']); ?>

    <div class="box box-solid box-success">

        <div class="box-header with-border">
            <h3 class="box-title">Пользователь GS:<?= $model->Name ?></h3>
        </div>

        <div class="box-body">

            <p>
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
            </p>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'headerOptions' => array('style' => 'width: 75px;'),
                        'attribute' => 'aid',
                        'value' => function($model) {
                            /** @var \common\models\LoginsSearch $model */
                            return Html::a(
                                $model['aid'].' (назначенные права)',
                                'https://office.gemotest.ru/administrator/index.php?r=auth/assignment/view&id='.$model['aid'],
                                [
                                    'title' => $model['aid'],
                                    'target' => '_blank'
                                ]
                            );
                        },
                        'format' => 'raw',
                    ],
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
                        'label' => 'Почта (директор)',
                        'attribute' => 'directorInfo.email',
                        'visible' => $model->UserType == 9 ? true : false,
                        'value' => function($model) {
                            /** @var \common\models\LoginsSearch $model */
                            return Html::mailto($model->directorInfo->email);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'label' => 'Подключенные отделения',
                        'format' => 'raw',
                        'visible' => $model->UserType == 9 ? true : false,
                        'value' => function($model){
                            $html = '';
                            /** @var $model \common\models\Logins */
                            if (isset($model->directorInfoSender)) {
                                /** @var \common\models\DirectorFloSender $object */
                                foreach ($model->directorInfoSender as $object) {
                                    $html .= " ".Html::tag(
                                            'span',
                                            $object->floName->Name,
                                            ['class' => 'label label-success']);
                                }
                            }
                            return $html;
                        },
                    ],
                    [
                        'label' => 'Моб. номер (директор)',
                        'attribute' => 'directorInfo.phoneNumber',
                        'visible' => $model->UserType == 9 ? true : false
                    ],
                    [
                        'headerOptions' => array('style' => 'width: 75px;'),
                        'attribute' => 'email',
                        'value' => function($model) {
                            /** @var \common\models\LoginsSearch $model */
                            $row = Html::mailto($model->Email);
                            if ($model->directorInfo) {
                                $row .= " " . Html::SubmitButton('Сбросить пароль', [
                                        'name' => 'reset-pass-gd',
                                        'class' => 'btn btn-primary btn-sm',
                                        'value' => '1'
                                    ]);
                            }
                            return $row;
                        },
                        'format' => 'raw',
                    ],
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
                ],
            ]) ?>
        </div>
    </div>

    <?php if ($model->adUsers || $model->adUserAccounts) : ?>

        <div class="box box-solid box-info">

            <div class="box-header with-border">
                <h3 class="box-title">Пользователь AD: <?= $model->adUsers->AD_name ?></h3>
            </div>

            <div class="box-body">

                <p>
                    <?= Html::SubmitButton($activeGS == 'active' ? 'Вкл. только AD авторизацию' : 'Вкл. авторизацию GS и AD', [
                        'name' => 'active-gs',
                        'class' => $activeGS == 'active' ? 'btn btn-success' : 'btn btn-info',
                        'value' => $activeGS,
                        'style' => 'display: '.$display
                    ]) ?>
                    <?php if ($model->UserType == 8) : ?>
                        <?= Html::a('Удалить', ['delete', 'ad' => $ad], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Удалить запись?',
                                'method' => 'post',
                            ],
                        ]);
                        ?>
                    <?php endif; ?>
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
                    ],
                ]) ?>

            </div>
        </div>

    <?php endif; ?>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->aid, 'ad' => $ad], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php ActiveForm::end(); ?>

</div>