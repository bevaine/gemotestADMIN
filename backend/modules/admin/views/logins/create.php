<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\AddUserForm */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Создание пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="logins-create">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Пользователи</a></li>
                <li class=""><a href="<?php echo Url::to(["logins/create-org"]) ?>">Юр. лица</a></li>
                <li class=""><a href="<?php echo Url::to(["logins/create-doc"]) ?>">Врач. иное</a></li>
                <li class=""><a href="<?php echo Url::to(["logins/create-franch"]) ?>">Франчайзи</a></li>
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

                        <div class="form-group">
                            <?= Html::Button('Создать',['class' => 'btn btn-success']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function checkAD (department, last_name, first_name, middle_name)
        {
            $.ajax({
                url: '/admin/logins/ajax-for-active',
                data: {
                    department:department,
                    last_name:last_name,
                    first_name:first_name,
                    middle_name: middle_name
                },
                success: function (res) {
                    if (res === 'null') {
                        $("#form-input").submit();
                    } else {
                        res = JSON.parse(res);
                        console.log(res.length);
                        if (res.length > 1) {
                            var html = "";
                            var htm_header = "";
                            for(var i = 0; i < res.length; i++){
                                html += '<label><input type="radio" name="radioAccountsList" value="'+res[i].account+'">'+res[i].account+' (email: '+res[i].email+')</label>';
                                html += '<input type="hidden" name="hiddenEmailList['+res[i].account+']" value="'+res[i].email+'">';
                            }
                            htm_header = '<p>У пользователя ';
                            htm_header += '<b>' + last_name + ' ' + first_name + ' ' + middle_name + '</b>';
                            htm_header += ' несколько УЗ в Active Directory</p>';
                            htm_header += 'Выбирите аккаунт AD на основании которого нужно создать УЗ Gomotest';
                            $('#modal-header').html(htm_header);
                            $('#modal-body').html(html);
                            $('#deactivate-user').modal('show');
                        } else if(res.length === 1) {
                            var html1 = "";
                            html1 += '<input type="hidden" name="radioAccountsList" value="'+res[0].account+'">';
                            html1 += '<input type="hidden" name="hiddenEmailList['+res[0].account+']" value="'+res[0].email+'">';
                            $('#account-hide').html(html1);
                            $("#form-input").submit();
                        } else {
                            $("#form-input").submit();
                        }
                    }
                }
            });
        }
    </script>
<?php
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