<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Doctors;
use kartik\select2;

/* @var $this yii\web\View */
/* @var $model common\models\AddUserForm */
/* @var $form yii\widgets\ActiveForm */
/* @var $action string */

$this->title = 'Создание пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="logins-create">

        <div class="nav-tabs-custom">

            <ul class="nav nav-tabs">
                <li class="<?= ($action == 'user') ? "active" : '' ?>"><a href="<?php echo Url::to(["logins/create/user"]) ?>">Пользователь</a></li>
                <li class="<?= ($action == 'franch') ? "active" : '' ?>"><a href="<?php echo Url::to(["logins/create/franch"]) ?>">Франчайзи</a></li>
                <li class="<?= ($action == 'doc') ? "active" : '' ?>"><a href="<?php echo Url::to(["logins/create/doc"]) ?>">Врач конс.</a></li>
                <li class="<?= ($action == 'gd') ? "active" : '' ?>"><a href="<?php echo Url::to(["logins/create/gd"]) ?>">Ген. директор</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="logins-form">

                        <?php $form = ActiveForm::begin(['id'=>'form-input']); ?>

                        <div class="modal fade" id="deactivate-user" tabindex="-1" role="dialog" aria-labelledby="deactivateLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-header" id="modal-header"></div>
                                    <div class="modal-body" id="modal-body">

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                                        <?= Html::submitButton('Продолжить', ['class' => 'btn btn-success']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div name="account-hide" id="account-hide"></div>
                        <input type="hidden" name="action-hide" class="action-hide" id="action-hide" value="<?= $action ?>">

                        <?php if ($action == 'user') : ?>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?php
                                        echo Html::label('Департамент');
                                        echo select2\Select2::widget([
                                            'model' => $model,
                                            'data' => \common\models\AddUserForm::getDepartments(7),
                                            'attribute' => 'department',
                                            'options' => [
                                                //'placeholder' => 'Без прав'
                                            ],
                                            'addon' => [
                                                'prepend' => [
                                                    'content' => Html::a('','#', [
                                                        'class' => 'glyphicon glyphicon-pencil'
                                                    ])
                                                ],
                                            ]
                                        ]);
                                        ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'operatorofficestatus')->textInput() ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'lastName')->textInput() ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'firstName')->textInput() ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'middleName')->textInput() ?>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>

                        <?php if ($action == 'doc') : ?>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?php
                                        $getDoctorsList = Doctors::getDoctorsList();
                                        echo $form->field($model, 'docId')->dropDownlist($getDoctorsList['arrValues'], array_merge(['prompt' => '---', 'disabled' => false], $getDoctorsList['arrOptions']))
                                        ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'specId')->dropDownlist(\common\models\SprDoctorSpec::getKeysList(), ['prompt' => '---', 'disabled' => false]); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($action == 'franch') : ?>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'key')->dropDownlist(\common\models\AddUserForm::getKeysList(), ['prompt' => '---', 'disabled' => false]); ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'operatorofficestatus')->textInput() ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'lastName')->textInput() ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'firstName')->textInput() ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'middleName')->textInput() ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($action == 'gd') : ?>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?php
                                        $getGdFloList = \common\models\DirectorFloSender::getGdFloList();
                                        echo $form->field($model, 'key')
                                            ->dropDownlist(
                                                $getGdFloList['arrValues'],
                                                array_merge(
                                                    ['prompt' => '---', 'disabled' => false],
                                                    $getGdFloList['arrOptions']
                                                )
                                            );
                                        ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'email')->textInput() ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'phone')->textInput() ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'lastName')->textInput() ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'firstName')->textInput() ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'middleName')->textInput() ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <p>
                        <?php
                            if ($action == 'user' || $action == 'franch' || $action == 'doc' || $action == 'gd') {
                                echo Html::Button('Создать', ['class' => 'btn btn-success']);
                            }
                            echo " ";
                            echo Html::a('Роли', [Url::to(["./skynet-roles"])], ['target' => '_blank', 'class' => 'btn btn-primary']) ?>
                        </p>
                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function checkAD (
            type = null,
            department = null,
            key_fr = null,
            key_doc,
            last_name,
            first_name,
            middle_name
        )
        {
            if (department === '4' || department === '5') {
                $("#form-input").submit();
            } else {
                $.ajax({
                    url: '/admin/logins/ajax-for-active',
                    data: {
                        type: type,
                        gd_id: key_fr,
                        doc_id: key_doc,
                        last_name: last_name,
                        first_name: first_name,
                        middle_name: middle_name
                    },
                    success: function (res) {
                        if (res === 'null') {
                            $("#form-input").submit();
                        } else {
                            let html = "";
                            let AD_text = "";
                            let GS_text = "";
                            let GD_text = "";
                            let AD_html_text = "";
                            let GS_html_text = "";
                            const ad = res.ad;
                            const gd = res.gd;
                            const gs = res.gs;
                            const htm_header = 'Информация по учетным записям';
                            if (gs !== undefined && gs.length > 0) {
                                GS_text += '<p>Пользователь уже имеет учетные записи в GemoSytems</p>';
                                GS_text += 'Выбирите аккаунт на основании которого нужно создать УЗ GemoSystem:';
                                for (let e = 0; e < gs.length; e++) {
                                    let checked = '';
                                    if (e === 0) checked = 'checked';
                                    GS_text += '<p><label><input type="radio" name="radioAIDList" value="' + gs[e].aid + '" ' + checked + '>' + gs[e].Name + '</label></p>';
                                }
                                GS_text += '<p><label><input type="radio" name="radioAIDList" value="new">Создать новую учетную запись GS</label></p>';
                            }
                            if (ad !== undefined && ad.length > 0) {
                                AD_text += '<p>Пользователь уже имеет учетные записи в Active Directory</p>';
                                AD_text += 'Выбирите аккаунт AD на основании которого нужно создать УЗ GemoSystem:';
                                for (let i = 0; i < ad.length; i++) {
                                    let style = "";
                                    let txtComment = "";
                                    if (ad[i].active === 1) {
                                        style = ' style="color:#ec1c24;font-weight:bold;" ';
                                        txtComment = ' - уже используется';
                                    }
                                    const dataUser = ad[i].name + '<br>(' + ad[i].account + ', email: ' + ad[i].email + ')' + txtComment;
                                    AD_text += '<label' + style + '><input type="radio" name="radioAccountsList" value="' + ad[i].account + '">' + dataUser +'</label>';
                                    AD_text += '<input type="hidden" name="hiddenEmailList[' + ad[i].account + ']" value="' + ad[i].email + '">';
                                }
                                AD_text += '<p><label><input type="radio" name="radioAccountsList" value="new">Создать новую учетную запись AD</label></p>';
                                AD_text += '<p><label><input type="checkbox" name="checkResetPassword">Сменить пароль</label></p>';
                            }
                            if (gd !== undefined && type === 'gd') {
                                GD_text += '<b>Примечание:</b> <p>На отделении <b>' + key_fr + '</b> уже есть назначенный директор <b>' + gd + '</b></p>';
                                GD_text += '<input type="hidden" name="AddUserForm[changeGD]" value="1">';
                            }
                            if (GS_text !== '' ||  AD_text !== '' || GD_text !== '') {
                                if (GS_text !== '') {
                                    GS_html_text = '<div class="box box-solid box-success">';
                                    GS_html_text += '<div class="box-header with-border">';
                                    GS_html_text += '<h3 class="box-title">Учетная запись SkyNet</h3>';
                                    GS_html_text += '</div>';
                                    GS_html_text += '<div class="box-body">' + GS_text + '</div>';
                                    GS_html_text += '</div>';
                                }
                                if (AD_text !== '') {
                                    AD_html_text = '<div class="box box-solid box-info">';
                                    AD_html_text += '<div class="box-header with-border">';
                                    AD_html_text += '<h3 class="box-title">Учетная запись Active Directory</h3>';
                                    AD_html_text += '</div>';
                                    AD_html_text += '<div class="box-body">' + AD_text + '</div>';
                                    AD_html_text += '</div>';
                                }
                                html = GS_html_text + AD_html_text;
                                if (GD_text !== '') {
                                    if (html !== '') html += '<hr />';
                                    html += GD_text;
                                }
                                $('#modal-header').html(htm_header);
                                $('#modal-body').html(html);
                                $('#deactivate-user').modal('show');
                            } else {
                                $("#form-input").submit();
                            }
                        }
                    }
                });
            }
        }
    </script>
<?php

if ($action == 'user' ||
    $action == 'franch' ||
    $action == 'doc' ||
    $action == 'gd'
) {
    $js1 = <<< JS
        $(".btn-success").click(function() { 
            if ($('#adduserform-lastname').val() !== ''  
            && $('#adduserform-firstname').val() !== '') {
                checkAD(
                    $('#action-hide').val(),
                    $('#adduserform-department').val(),
                    $('#adduserform-key').val(),
                    $('#adduserform-docid').val(),
                    $('#adduserform-lastname').val(),
                    $('#adduserform-firstname').val(),
                    $('#adduserform-middlename').val()
                );
            }
        });

        $(".glyphicon-pencil").click(function() {
            let department = $('#adduserform-department').val();
            if (department !== null) {
                window.open("/admin/skynet-roles/update?id=" + department, '_blank');
            }
        });
JS;
    $this->registerJs($js1);
}
?>