<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use budyaga\users\UsersAsset;
use common\models\AddUserForm;

/* @var $department */
$this->title = 'Редактирование ролей пользователей';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

UsersAsset::register($this);
?>

<div class="permission-children-editor">
    <?php $form = ActiveForm::begin(['id'=>'form-input']); ?>
    <div class="roles-form">
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group department">
                    <?= Html::label('Права') ?>
                    <?= Html::dropDownList(
                            'Permissions[department]',
                            $department,
                                AddUserForm::getDepartments() + AddUserForm::getMainDepartments(),
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
            <div class="col-lg-4">
                <div class="form-group erp_users">
                    <?= Html::label('Права в модуле выездного обслуживания') ?>
                    <?= Html::dropDownList(
                        'Permissions[erp-user-groups]',
                        'null',
                        \common\models\ErpUsergroups::getErpGroupsList(),
                        [
                            'class' =>'form-control',
                            'id' => 'erp_user_groups',
                            'prompt' => 'нет',
                        ]
                    ) ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="form-group erp_nurse">
                    <? //Html::label('Добавить в справочник выездных медсестер') ?>
                    <?= Html::checkbox(
                        'Permissions[erp-user-nurse]',
                        \common\models\ErpGroupsRelations::getNurse($department),
                        [
                            'label' => 'Добавить в справочник выездных медсестер',
                            'id' => 'erp_user_nurse',
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
            let optionsAsString1 = 0;
            const permission = res.permission;
            const erp_groups = res.erp_groups;
            const erp_nurse = res.erp_nurse;

            if (permission !== undefined && permission.length > 0) {
                for (let i1 = 0; i1 < permission.length; i1++) {
                    optionsAsString1 += "<option value='" + permission[i1]['id'] + "'>" + permission[i1]['text'] + "</option>";
                }
            }
            $(".permission select option").each(function () {
                $(this).remove();
            });
            $(".permission select").append(optionsAsString1);

            $('#erp_user_groups').val(erp_groups);

            $('#erp_user_nurse').attr('checked', erp_nurse);
        }
    });
}
</script>

<?php
    $js = <<< JS
        $(".department select").change(function() { 
            checkAD($('#permissions-department').val());
        });

        $(".erp_users select").change(function() { 
            $("#form-input").submit();
        });
        
        $(".erp_nurse input").change(function() { 
            $("#form-input").submit();
        });

        $(document).ready(function() { 
            checkAD($('#permissions-department').val());
        })
JS;

    $this->registerJs($js);
?>
