<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use budyaga\users\UsersAsset;
use common\models\AddUserForm;

/* @var $this yii\web\View */
/* @var $model common\models\SkynetRoles */
/* @var $form yii\widgets\ActiveForm */

UsersAsset::register($this);
//print_r(\common\models\AddUserForm::getTypeList());
//exit;
?>

<style>
    .txtBoxStyle {
        min-width: 30px;
        max-width: 120px;
    }
</style>

<div class="skynet-roles-form">

    <div class="permission-children-editor">

        <?php $form = ActiveForm::begin(['id'=>'form-input']); ?>

        <div class="roles-form">

            <div class="well">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group type">
                            <?php
                                $getTypeList = AddUserForm::getTypeList();
                                echo $form->field($model, 'type')
                                    ->dropDownlist(
                                        $model->isNewRecord ? $getTypeList['arrValues'] : AddUserForm::getTypes(),
                                        array_merge(
                                            ['id' => 'skynet-type', 'disabled' => !$model->isNewRecord],
                                            $model->isNewRecord ? $getTypeList['arrOptions'] : []
                                        )
                                    );
                            ?>
                        </div>
                    </div>
                    <div class="col-lg-4" id="type-frame" >
                        <div class="form-group department">
                            <?= $form->field($model, 'name')->textInput() ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="well">

                <div class="panel panel-default" id="panel-main" data-id="main" style="display:none;">
                    <div class="panel-heading"><b>Основные параметры учетной записи SkyNet</b></div>
                    <div id="body-main" class="panel-body" style="display:none;">
                        <?= Html::hiddenInput('structure[main]', '1') ?>
                        <div id="frame-main"></div>
                    </div>
                </div>

                <div class="panel panel-default" id="panel-operator" data-id="operator" style="display:none;">
                    <div class="panel-heading">
                        <?= Html::checkbox(
                            'structure[operator]',
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

                <div class="panel panel-default" id="panel-ad_authorization" data-id="ad_authorization" style="display:none;">
                    <div class="panel-heading">
                        <?= Html::checkbox(
                            'structure[ad_authorization]',
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

                <div class="panel panel-default" id="panel-erp" data-id="erp" style="display:none;">
                    <div class="panel-heading">
                        <?= Html::checkbox(
                            'structure[erp]',
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
                                        'structure[nurse]',
                                        false,
                                        [
                                            'label' => 'Добавить в список медсестер выездного обслуживания',
                                            'id' => 'nurse',
                                        ]
                                    ) ?>
                                </div>
                            </div>
                        </div>

                        <div id="body-nurse" style="display:none;">
                            <div id="frame-nurse"></div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default" id="panel-party" data-id="party" style="display:none;">
                    <div class="panel-heading">
                        <?= Html::checkbox(
                            'structure[party]',
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
                                        'info[counterparty_id]',
                                        null,
                                        [
                                            '[1]' => 'СЛО',
                                            '[10]' => 'ФЛО',
                                            '[1,10]' => 'СЛО и ФЛО'
                                        ],
                                        [
                                            'id' => 'counterparty_id',
                                        ]
                                    ) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default" id="panel-doctor_consultant" data-id="doctor_consultant" style="display:none;">
                    <div class="panel-heading">
                        <?= Html::checkbox(
                            'structure[doctor_consultant]',
                            false,
                            [
                                'label' => 'Доктор-консультант',
                                'id' => 'doctor_consultant',
                            ]
                        ) ?>
                    </div>
                    <div class="panel-body" style="display:none;" id="body-doctor_consultant">
                        <div id="frame-doctor_consultant"></div>
                    </div>
                </div>

                <div class="panel panel-default" id="panel-director" data-id="director" style="display:none;">
                    <div class="panel-heading">
                        <?= Html::checkbox(
                            'structure[director]',
                            false,
                            [
                                'label' => 'Директор',
                                'id' => 'director',
                            ]
                        ) ?>
                    </div>
                    <div class="panel-body" style="display:none;" id="body-director">
                        <div id="frame-director"></div>
                    </div>
                </div>

            </div>

            <div class="well">

                <div class="row" id="frame-permissions">
                    <div class="col-xs-5 children-list">
                        <?= Html::label('Роли назначенные на права') ?>
                        <div class="form-group">
                            <input type="text" class="form-control listFilter" placeholder="поиск по названию">
                        </div>
                        <div class="form-group permission">
                            <?= Html::dropDownList('SkynetRoles[permission]', null, [], ['id' => 'permission', 'label' => "we",'multiple' => 'multiple', 'size' => '20', 'class' => 'col-xs-12']) ?>
                        </div>
                    </div>
                    <div class="col-xs-2 text-center">
                        <button id="assign" class="btn btn-success" type="button" name="ErpGroupsRelations[action]" value="assign"><span class="glyphicon glyphicon-arrow-left"></span></button>
                        <button id="revoke" class="btn btn-success" type="button" name="ErpGroupsRelations[action]" value="revoke"><span class="glyphicon glyphicon-arrow-right"></span></button>
                    </div>
                    <div class="col-xs-5 children-list">
                        <?=  Html::label('Какие роли назначить') ?>
                        <div class="form-group">
                            <input type="text" class="form-control listFilter" placeholder="поиск по названию">
                        </div>
                        <?= Html::dropDownList('SkynetRoles[list_permission]', 'null', \common\models\NAuthItem::getListName(), ['id' => 'list_permission', 'multiple' => 'multiple', 'size' => '20', 'class' => 'col-xs-12']); ?>
                    </div>
                </div>

            </div>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['id' => 'submitButton', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$type = !empty($model->type) ? $model->type : '7';
$department = !empty($model->id) ? $model->id : '""';
$structure_json = !empty($model->structure_json) ? $model->structure_json : '""';
$tables_json = !empty($model->tables_json) ? $model->tables_json : '""';
$info_json = !empty($model->info_json) ? $model->info_json : '""';

$js = <<< JS
    const isNew = '{$model->isNewRecord}';  
    const type = '{$type}';
    const department = '{$department}';
    const structure_json = {$structure_json};
    const tables_json = {$tables_json};
    const info_json = {$info_json};
   
    $(document).ready(function() 
    {
        setType();
        showHideDepartment(type);
        constructBody(type);
        addPermissions(department);
    });
       
    $('#assign').click(function() 
    {
        moveItems('#list_permission', '#permission');
    });

    $('#revoke').click(function() 
    {
        moveItems('#permission', '#list_permission');
    });
        
    $(".panel-heading input[type=checkbox]").change(function() {
        showTable($(this));        
    });
    
    $(".nurse input[type=checkbox]").change(function() {
        showTable($(this));    
    });
        
    $('#skynet-type').change(function() 
    {
        resetForm($(this));
        let name = $("#skynet-type option:selected").text();
        setFormVal('SkynetRoles[name]', name);
        showHideDepartment($(this).val());
        constructBody($(this).val())
    });
    
    $("#submitButton").click(function() 
    { 
        $('#permission option').prop('selected', true);
    });
    
    function constructBody(type) 
    {
        $.ajax({
            url: '/admin/logins/ajax-list-table',
            data: {type: type},
            success: function(res) {
                if (res !== undefined && res !== null) 
                {
                    $.each(res.result, function(module_name, tables) 
                    {
                        console.log(module_name);
                        console.log(tables);
                        if (module_name === 'franchazy') {
                            disablePermmissions();
                        } else if (module_name === 'main') {
                            $('#body-main').css('display', "block");
                        } else if (module_name === 'party') {
                            addParty();
                        } 
                        if (checkDisable(type, module_name)) {
                            setFormVal('structure[' + module_name + ']', '1');
                        }
                        $('#panel-' + module_name).css('display', "block");
                        $('#frame-' + module_name).html(createTable(tables));
                        
                        let object = $('#' + module_name);                        
                        if ((structure_json !== '' 
                            && $.inArray(module_name, structure_json) !== -1)
                                || checkDisable(type, module_name)
                        ){
                            object.prop('checked', true);
                            showTable(object);
                        }
                        if (checkDisable(type, module_name)) {
                            object.prop('disabled', true);
                        }
                    });
                    setTable();
                }
            }
        }); 
    }
    
    function checkDisable(type = null, module_name = null) 
    {
        return (module_name === 'operator')
            || (type === '5' && module_name === 'doctor_consultant')
            || (type === '8' && module_name === 'ad_authorization')
            || (type === '9' && module_name === 'director');
        
    }

    function createTable(object) 
    {
        let html = '';
        $.each(object, function(class_name, val_rows) 
        { 
            let tr_title = '';
            let tr_row = '';
            let type_str = ''; 
            $.each(val_rows, function(key, val) 
            { 
                type_str = val.type === 'integer' ? 'number' : 'text';
                tr_title += '<th>' + val.title + '</th>';
                tr_row += '<th align="center">';
                tr_row += '<input type="' + type_str + '"';
                tr_row += ' class="form-control txtBoxStyle" name="tables[' + class_name + '][' + val.name + ']">';
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
        return html;
    }
    
    function resetForm(object) 
    {
        let val = object.val();
        $('#form-input')[0].reset();
        object.val(val);
        $('.panel').each(function() {
            $(this).css('display', 'none');
            $(this).find('.panel-body').css('display', 'none');
        });
        $("#frame-permissions")
            .find("input,select")
            .removeAttr("disabled");
    }

    function disablePermmissions() {
        $("#frame-permissions").find("input,select").attr('disabled', 'disabled');
    }
    
    function showHideDepartment(type) 
    {
        $('#type-frame').css('display', type !== '7' ? 'none' : "block");
    }
    
    function setType() {
        if (isNew !== '1') {
            setFormVal('SkynetRoles[type]', type);
        }
    } 
   
    /**
    * 
    * @param name
    * @param val
    */
    function setFormVal(name, val) 
    {
        if ($("input").is("#" + name)) {
            $("#" + name).remove();
        }
        $("<input>").attr({
            type: "hidden",
            id: name,
            name: name,
            value: val
        }).appendTo("form");
    }

    /**
    * 
    */
    function addParty() 
    {
        if (info_json !== '')
        {
            let misInput = $('#counterparty_id')
                .find('input[value="[' + info_json.counterparty_id.toString() + ']"]');
            if (misInput !== undefined) {
                misInput.prop('checked', true);
            }
        }
    }
    
    /**
    * 
    */
    function setTable() 
    {
        $.each(tables_json, function(module, check) 
        {
            for (let field in check) {
                let value = check[field];
                let type = 'input';
                if (basename(module) === 'ErpUsers' && field === 'group_id') {
                    type = 'select';
                }
                let findField = $(type + '[name="tables[' + basename(module) + '][' + field + ']"]'); 
                if (findField.length) {
                    findField.val(value);
                }
            }
        });
    }
    
    /**
    * @param object
    */
    function showTable(object) 
    {
        let name = object.attr('id');
        let check = object.prop('checked');
        $('#body-' + name).css('display', check === true ? "block" : 'none');
    }

    /**
    * 
    * @param origin
    * @param dest
    */
    function moveItems(origin, dest) 
    {
        let selected = $(origin).find(':selected');
        if (selected.length) {
            for (let i = 0; i < selected.length; i++) { 
                let parent = selected[i].parentNode; 
                let findParent = $(dest).find('optgroup[label="' + parent.label + '"]');
                if (!findParent.length) {
                    $('<optgroup label="' + parent.label + '" />').appendTo(dest);
                    findParent = $(dest).find('optgroup[label="' + parent.label + '"]');
                }
                $(origin + ' option[value="' + selected[i].value + '"]').appendTo(findParent); 
                if (!parent.childNodes.length) {
                    parent.remove();
                }
            }
        }
    }
    
    function addPermissions(department) 
    {
        $.ajax({
            url: '/admin/logins/ajax-permissions',
            data: {
                department: department,
            },
            success: function(res) {
                if (res !== undefined && res !== null){
                    for (let check in res.result) {
                        $('#list_permission option[value="' + check + '"]').prop('selected', true);
                    }
                    moveItems('#list_permission', '#permission');
                }
            }
        });
    }
     
    /**
    * @param path
    * @returns {T|*}
    */
    function basename(path) {
       return path.split("\\\\").reverse()[0];
    }
JS;

$this->registerJs($js);