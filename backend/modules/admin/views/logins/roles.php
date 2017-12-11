<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use budyaga\users\UsersAsset;

/* @var $department */
$this->title = 'Редактирование ролей пользователей';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

UsersAsset::register($this);
?>

<div class="permission-children-editor">
    <?php $form = ActiveForm::begin(); ?>
    <div class="roles-form">
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group department">
                    <?= Html::label('Права:') ?>
                    <?= Html::dropDownList(
                            'Permissions[department]',
                            $department,
                            \common\models\AddUserForm::getDepartments(),
                            [
                                'options' => [7 => ['disabled' => true]],
                                'class' =>'form-control',
                                'id' => 'permissions-department'
                            ]
                    ) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-5 children-list">
                <?= Html::label('Роли назначенные на права') ?>
                <div class="form-group">
                    <input type="text" class="form-control listFilter" placeholder="поиск по названию">
                </div>
                <div class="form-group permission">
                    <?= Html::dropDownList('Permissions[permission]', null, [], ['label' => "we",'multiple' => 'multiple', 'size' => '20', 'class' => 'col-xs-12']) ?>
                </div>
            </div>
            <div class="col-xs-2 text-center">
                <button class="btn btn-success" type="submit" name="Permissions[action]" value="assign"><span class="glyphicon glyphicon-arrow-left"></span></button>
                <button class="btn btn-success" type="submit" name="Permissions[action]" value="revoke"><span class="glyphicon glyphicon-arrow-right"></span></button>
            </div>
            <div class="col-xs-5 children-list">
                <?=  Html::label('Какие роли назначить') ?>
                <div class="form-group">
                    <input type="text" class="form-control listFilter" placeholder="поиск по названию">
                </div>
                <?= Html::dropDownList('Permissions[list-permission]', 'null', \common\models\NAuthItem::getListName(), ['multiple' => 'multiple', 'size' => '20', 'class' => 'col-xs-12']); ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    function checkAD (department = null)
    {
    $.ajax({
        url: '/admin/logins/ajax-list-name',
        data: {
            department: department
        },
        success: function(res) {
            var optionsAsString = 0;
            res = JSON.parse(res);
            if (res !== null) {
                if (res.results.length > 0) {
                    for (var i = 0; i < res.results.length; i++) {
                        optionsAsString += "<option value='" + res.results[i]['id'] + "'>" + res.results[i]['text'] + "</option>";
                    }
                }
            }
            $(".permission select option").each(function () {
                $(this).remove();
            });
            $(".permission select").append(optionsAsString);
        }
    });
}
</script>

<?php
    $js = <<< JS
        $(".department select").change(function() { 
            checkAD($('#permissions-department').val());
        });

        $(document).ready(function() { 
            checkAD($('#permissions-department').val());
        })
JS;

    $this->registerJs($js);
?>
