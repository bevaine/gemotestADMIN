<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

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
                <li class="<?= ($action == 'org') ? "active" : '' ?>"><a href="<?php echo Url::to(["logins/create/org"]) ?>">Юр. лица</a></li>
                <li class="<?= ($action == 'doc') ? "active" : '' ?>"><a href="<?php echo Url::to(["logins/create/doc"]) ?>">Врач. иное</a></li>
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

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'department')->dropDownlist(\common\models\AddUserForm::getDepartments()) ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'type')->dropDownlist(\common\models\AddUserForm::getTypesArray()) ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'nurse')->dropDownlist(\common\models\AddUserForm::getNurses()) ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'operatorofficestatus')->textInput() ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($action == 'org') : ?>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'name')->textInput() ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'key')->textInput() ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'login')->textInput() ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'email')->textInput() ?>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <?= $form->field($model, 'blankText')->textarea() ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($action == 'doc') : ?>
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
                            if ($action == 'user' || $action == 'franch') {
                                echo Html::Button('Создать', ['class' => 'btn btn-success']);
                            } else {
                                echo Html::submitButton('Создать', ['class' => 'btn btn-success']);
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
        function checkAD (department = null, last_name, first_name, middle_name)
        {
            if (department === '4' || department === '5') {
                $("#form-input").submit();
            } else {
                $.ajax({
                    url: '/admin/logins/ajax-for-active',
                    data: {
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
                                    var dataUser = res[i].name + '<br>(' + res[i].account + ', email: ' + res[i].email + ')';
                                    html += '<label><input type="radio" name="radioAccountsList" value="' + res[i].account + '">' + dataUser +'</label>';
                                    html += '<input type="hidden" name="hiddenEmailList[' + res[i].account + ']" value="' + res[i].email + '">';
                                }
                                html += '<p><label><input type="radio" name="radioAccountsList" value="new">Создать новую учетную запись</label></p>';
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

if ($action == 'user' || $action == 'franch') {
    $js = <<< JS
        $(".btn-success").click(function() { 
            checkAD(
                $('#adduserform-department').val(),
                $('#adduserform-lastname').val(),
                $('#adduserform-firstname').val(),
                $('#adduserform-middlename').val());
            });
JS;

    $this->registerJs($js);
}