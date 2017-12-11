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
                <li class="<?= ($action == 'user') ? "active" : '' ?>"><a href="<?php echo Url::to(["logins/create/user"]) ?>">Пользователи</a></li>
                <li class="<?= ($action == 'doc') ? "active" : '' ?>"><a href="<?php echo Url::to(["logins/create/doc"]) ?>">Врач конс.</a></li>
                <li class="<?= ($action == 'franch') ? "active" : '' ?>"><a href="<?php echo Url::to(["logins/create/franch"]) ?>">Франчайзи</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="logins-form">

                        <?php $form = ActiveForm::begin(['id'=>'form-input']); ?>

                        <div class="modal fade" id="deactivate-user" tabindex="-1" role="dialog" aria-labelledby="deactivateLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header" id="modal-header"></div>
                                    <div class="modal-body" id="modal-body"></div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                                        <?= Html::submitButton('Выбрать', ['class' => 'btn btn-success']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div name="account-hide" id="account-hide"></div>

                        <?php if ($action == 'user') : ?>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?php
                                        echo Html::label('Департамент:');
                                        echo select2\Select2::widget([
                                            'model' => $model,
                                            'data' => \common\models\AddUserForm::getDepartments(),
                                            'attribute' => 'department',
                                            'addon' => [
                                                'prepend' => [
                                                    'content' => Html::a(
                                                        '',
                                                        Url::to('roles'), [
                                                            'class' => 'glyphicon glyphicon-pencil',
                                                            'target' => '_blank'
                                                        ]
                                                    )
                                                ],
                                            ]
                                        ]);
                                        ?>
                                    </div>

                                </div>
                                    <?php //Html::a('', Url::to('roles'), ['class' => 'glyphicon glyphicon-pencil']) ?>
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

                        <div class="form-group">
                            <?php
                            if ($action == 'user' || $action == 'franch' || $action == 'doc') {
                                echo Html::Button('Создать', ['class' => 'btn btn-success']);
                            } else {
                                //echo Html::submitButton('Создать', ['class' => 'btn btn-success']);
                            }
                            ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function checkAD (department = null, key_doc, last_name, first_name, middle_name)
        {
            if (department === '4' || department === '5') {
                $("#form-input").submit();
            } else {
                $.ajax({
                    url: '/admin/logins/ajax-for-active',
                    data: {
                        doc_id: key_doc,
                        last_name: last_name,
                        first_name: first_name,
                        middle_name: middle_name
                    },
                    success: function (res) {
                        if (res === 'null') {
                            $("#form-input").submit();
                        } else {
                            res = JSON.parse(res);
                            //console.log(res.length);
                            if (res.length > 0) {
                                var html = "";
                                var htm_header = "";
                                for (var i = 0; i < res.length; i++) {
                                    var style = "";
                                    var txtComment = "";
                                    if (res[i].active === 1) {
                                        console.log(res[i].active);
                                        style = ' style="color:#ec1c24;font-weight:bold;" ';
                                        txtComment = ' - уже используется';
                                    }
                                    var dataUser = res[i].name + '<br>(' + res[i].account + ', email: ' + res[i].email + ')' + txtComment;
                                    html += '<label' + style + '><input type="radio" name="radioAccountsList" value="' + res[i].account + '">' + dataUser +'</label>';
                                    html += '<input type="hidden" name="hiddenEmailList[' + res[i].account + ']" value="' + res[i].email + '">';
                                }
                                html += '<p><label><input type="radio" name="radioAccountsList" value="new">Создать новую учетную запись</label></p>';
                                html += '<p><label><input type="checkbox" name="checkResetPassword">Сменить пароль</label></p>';
                                htm_header += '<p>У пользователя ';
                                htm_header += '<b>' + last_name + ' ' + first_name + ' ' + middle_name + '</b>';
                                htm_header += ' несколько УЗ в Active Directory</p>';
                                htm_header += 'Выбирите аккаунт AD на основании которого нужно создать УЗ Gomotest';
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

if ($action == 'user' || $action == 'franch' || $action == 'doc'  ) {
    $js = <<< JS
        $(".btn-success").click(function() { 
            checkAD(
                $('#adduserform-department').val(),
                $('#adduserform-docid').val(),
                $('#adduserform-lastname').val(),
                $('#adduserform-firstname').val(),
                $('#adduserform-middlename').val());
            });
JS;

    $this->registerJs($js);
}