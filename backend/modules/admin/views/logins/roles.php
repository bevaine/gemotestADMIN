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
<style>
    .txtBoxStyle {
        min-width: 30px;
        max-width: 120px;
    }
</style>

<div class="permission-children-editor">
    <?php $form = ActiveForm::begin(['id'=>'form-input']); ?>
    <div class="roles-form">
        <div class="well">

            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group department">
                        <?= Html::label('Название правила') ?>
                        <?= Html::input(
                            'text',
                            'ErpGroupsRelations[department]',
                            null,
                            [
                                'id' => 'permissions-department',
                                'class' => 'form-control floating-label',
                            ]
                        )?>
                    </div>
                </div>
            </div>

        </div>

        <div class="well">

            <div class="panel panel-default">
                <div class="panel-heading"><b>Основные параметры учетной записи SkyNet</b></div>
                <div id="body-main" class="panel-body" style="display:none;">
                    <div id="frame-main"></div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= Html::checkbox(
                        'ErpGroupsRelations[operator]',
                        false,
                        [
                            'label' => 'Добавить в "Операторы"',
                            'id' => 'operator',
                        ]
                    ) ?>
                </div>
                <div class="panel-body" style="display:none;" id="body-operator">
                    <div id="frame-operator"></div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= Html::checkbox(
                        'ErpGroupsRelations[ad_authorization]',
                        false,
                        [
                            'label' => 'Авторизация через "Active Directory"',
                            'id' => 'ad_authorization',
                        ]
                    ) ?>
                </div>
                <div class="panel-body" style="display:none;" id="body-ad_authorization">
                    <div id="frame-ad_authorization"></div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= Html::checkbox(
                        'ErpGroupsRelations[erp]',
                        false,
                        [
                            'label' => 'Добавить в модуль выездного обслуживания',
                            'id' => 'erp',
                        ]
                    ) ?>
                </div>

                <div style="display:none;" class="panel-body" id="body-erp">

                    <div id="frame-erp"></div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group group">
                                <?= Html::label('Права в модуле выездного обслуживания') ?>
                                <?= Html::dropDownList(
                                    'tables[ErpUsers][group_id]',
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
                            <div class="form-group nurse">
                            <?= Html::checkbox(
                                'ErpGroupsRelations[nurse]',
                                false,
                                [
                                    'label' => 'Добавить в список медсестер выездного обслуживания',
                                    'id' => 'nurse',
                                ]
                            ) ?>
                            </div>
                        </div>
                    </div>

                    <div id="frame-nurse"> </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= Html::checkbox(
                        'ErpGroupsRelations[party]',
                        false,
                        [
                            'label' => 'Добавить выбор отделения при входе в модуль "МИС"',
                            'id' => 'party',
                        ]
                    ) ?>

                </div>
                <div style="display:none;" class="panel-body" id="body-party">
                    <div id="frame-party"></div>
                    <div id="mis_div" class="row">
                        <div class="col-lg-4">
                            <div class="form-group mis-senders">
                                <?= Html::label('Какие типы отделений добавить для выбора в МИС модуле:') ?>
                                <?= Html::radioList(
                                    'ErpGroupsRelations[mis_access]',
                                    null,
                                    ['СЛО', 'ФЛО', 'СЛО и ФЛО'],
                                    [
                                        'id' => 'mis_access',
                                    ]
                                ) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="well">

            <div class="row">
                <div class="col-xs-5 children-list">
                    <?= Html::label('Роли назначенные на права') ?>
                    <div class="form-group">
                        <input type="text" class="form-control listFilter" placeholder="поиск по названию">
                    </div>
                    <div class="form-group permission">
                        <?= Html::dropDownList('ErpGroupsRelations[permission]', null, [], ['label' => "we",'multiple' => 'multiple', 'size' => '20', 'class' => 'col-xs-12']) ?>
                    </div>
                </div>
                <div class="col-xs-2 text-center">
                    <button class="btn btn-success" type="submit" name="ErpGroupsRelations[action]" value="assign"><span class="glyphicon glyphicon-arrow-left"></span></button>
                    <button class="btn btn-success" type="submit" name="ErpGroupsRelations[action]" value="revoke"><span class="glyphicon glyphicon-arrow-right"></span></button>
                </div>
                <div class="col-xs-5 children-list">
                    <?=  Html::label('Какие роли назначить') ?>
                    <div class="form-group">
                        <input type="text" class="form-control listFilter" placeholder="поиск по названию">
                    </div>
                    <?= Html::dropDownList('ErpGroupsRelations[list_permission]', 'null', \common\models\NAuthItem::getListName(), ['multiple' => 'multiple', 'size' => '20', 'class' => 'col-xs-12']); ?>
                </div>
            </div>

        </div>

        <div class="form-group">
            <?= Html::submitButton('Создать', ['class' => 'btn btn-success', 'id' => 'submitButton']) ?>
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
            let optionsAsString1 = 0, addMis = false;
            const permission = res.permission;
            const erp_groups = res.erp_groups;
            const erp_nurse = res.erp_nurse;
            const mis_access = res.mis_access;

            if (permission !== undefined && permission.length > 0) {
                for (let i1 = 0; i1 < permission.length; i1++)
                {
                    if ($.inArray(permission[i1]['id'], [
                        'mis',
                        'MisManager',
                        'MisAdmin',
                        'MisOfficeManager'
                        ]) !==  -1
                    ){
                        addMis = true;
                    }
                    optionsAsString1 += "<option value='" + permission[i1]['id'] + "'>" + permission[i1]['text'] + "</option>";
                }
            }
            if (addMis) {
                if (mis_access !== 'null') {
                    console.log(mis_access);
                    if (mis_access === '0') {
                        console.log($("#mis-senders select']"));
                        //$("input[name='ErpGroupsRelations[mis_access]']").val(mis_access);
                    } else if (mis_access === '1') {

                    } else if (mis_access === '2') {

                    }
                }


                $('#mis_div').css("display", "block");
            } else {
                $('#mis_div').css("display", "none");
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
        
        // $("#submitButton").click(function() { 
        //     const m_data = $('#form-input').serializeArray();
        //     console.log(m_data);
        // });
        
        
        let html_body = '';
    
        $(".department select").change(function() { 
            checkAD($('#permissions-department').val());
        });

        $(".erp_users select").change(function() { 
            $("#form-input").submit();
        });
        
        $("#operator").change(function() 
        { 
            let name = $(this).attr('id');
            let check = $(this).prop('checked');
            addTable(name, check);
            $('#body-' + name).css('display', check === true ? "block" : 'none');
        });        
        
        $("#ad_authorization").change(function() 
        { 
            let name = $(this).attr('id');
            let check = $(this).prop('checked');
            addTable(name, check);
            $('#body-' + name).css('display', check === true ? "block" : 'none');
        });        
        
        $("#erp").change(function() 
        { 
            let name = $(this).attr('id');
            let check = $(this).prop('checked');
            addTable(name, check);
            $('#body-' + name).css('display', check === true ? "block" : 'none');
        });
        
        $("#nurse").change(function() 
        { 
            let name = $(this).attr('id');
            let check = $(this).prop('checked');
            addTable(name, check);
            $('#body-' + name).css('display', check === true? "block" : 'none');
        });
        
        $("#party").change(function() 
        { 
            let name = $(this).attr('id');
            let check = $(this).prop('checked');
            addTable(name, check);
            $('#body-' + name).css('display', check === true ? "block" : 'none');
        });
        
        function addTable(name, checked) 
        {
            $('#frame-' + name).html('');
             
            if (checked === true) 
            {
                $.ajax({
                    url: '/admin/logins/ajax-list-table',
                    data: {
                        type: name,
                        all: false
                    },
                    success: function(res) {
                        let html = '';
                        if (res !== undefined && res !== null) 
                        {
                            $.each(res.result, function(class_name, val_rows) 
                            {
                                
                                let tr_title = '';
                                let tr_row = '';
                                $.each(val_rows, function(key, val) 
                                { 
                                    tr_title += '<th>' + val.title + '</th>';
                                    tr_row += '<th align="center">';
                                    tr_row += '<input type="text" class="form-control txtBoxStyle" name="tables[' + class_name + '][' + val.name + ']">';
                                    tr_row += '</th>';
                                });
                                html += '<b>' + class_name + '</b>';
                                html += '<table class="table table-striped">';
                                html += '<thead class="thead-inverse">';
                                html += '<tr>' + tr_title + '</tr>';
                                html += '<thead>';
                                html += '<thead class="thead-inverse">';
                                html += '<tr>' + tr_row + '</tr>';
                                html += '<thead>';
                                html += '</table>';
                            });
                            if (html !== '') {
                                $('#frame-' + name).html(html); 
                            }
                        }
                    }
                }); 
            }  
        }
        
        $(".mis-senders input").change(function() { 
            //$("#form-input").submit();
        });
        
        $(".erp_nurse input").change(function() { 
            $(".permission select option").prop('selected', true);
            //$("#form-input").submit();
        });
                

        $(document).ready(function() { 
            addTable('main', true);
            $('#body-main').css('display', "block");
            //checkAD($('#permissions-department').val());
        });
        
        function checkAD (department = null)
        {
        $.ajax({
            url: '/admin/logins/ajax-list-name',
            data: {
                department: department
            },
            success: function(res) {
                let optionsAsString1 = 0, addMis = false;
                const permission = res.permission;
                const erp_groups = res.erp_groups;
                const erp_nurse = res.erp_nurse;
                const mis_access = res.mis_access;
    
                if (permission !== undefined && permission.length > 0) {
                    for (let i1 = 0; i1 < permission.length; i1++)
                    {
                        if ($.inArray(permission[i1]['id'], [
                            'mis',
                            'MisManager',
                            'MisAdmin',
                            'MisOfficeManager'
                            ]) !==  -1
                        ){
                            addMis = true;
                        }
                        optionsAsString1 += "<option value='" + permission[i1]['id'] + "'>" + permission[i1]['text'] + "</option>";
                    }
                }
                if (addMis) {
                    if (mis_access !== 'null') {
                        console.log(mis_access);
                        if (mis_access === '0') {
                            console.log($("#mis-senders select']"));
                            //$("input[name='ErpGroupsRelations[mis_access]']").val(mis_access);
                        } else if (mis_access === '1') {
    
                        } else if (mis_access === '2') {
    
                        }
                    }
    
    
                    $('#mis_div').css("display", "block");
                } else {
                    $('#mis_div').css("display", "none");
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
JS;

$this->registerJs($js);
?>
