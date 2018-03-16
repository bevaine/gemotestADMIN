<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\GmsDevices;
use common\models\GmsRegions;
use common\models\GmsPlaylistOut;

/* @var $this yii\web\View */
/* @var $model common\models\GmsDevices */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gms-devices-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'device')->textInput(['maxlength' => true, 'disabled' => !$model->isNewRecord]) ?>
    <?= Html::hiddenInput('GmsDevices[device]', $model['device']) ?>

    <div class="form-group region_id">
        <?= $form->field($model, 'region_id')->dropDownList(GmsRegions::getRegionList(), ['prompt' => '---']); ?>
    </div>

    <div class="form-group sender_id">
        <?= $form->field($model, 'sender_id')->dropDownList([], ['prompt' => '---']); ?>
    </div>

    <div class="form-group auth_status">
        <?= $form->field($model, 'auth_status')->dropDownList(GmsDevices::getAuthStatusArray(), ['prompt' => '---']); ?>
    </div>

    <div class="form-group current_pls_id">
        <?= $form->field($model, 'current_pls_id')->dropDownList(GmsPlaylistOut::getPlayListArray(), ['prompt' => '---']); ?>
    </div>

    <div class="form-group timezone">
        <?= $form->field($model, 'timezone')->dropDownList(\common\components\helpers\FunctionsHelper::getTimeZonesList(), ['prompt' => '---']); ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$urlAjaxSender = \yii\helpers\Url::to(['/GMS/gms-senders/ajax-senders-list']);

$js1 = <<< JS
    /**
    * @param region
    */
    function setSender(region) {
        var senderSelect = $('.sender_id select');
        var senderDisable = senderSelect.prop('disabled');        
        senderSelect.attr('disabled', true);
        $.ajax({
            url: '{$urlAjaxSender}',
            data: {region: region},
            success: function (res) {
                res = JSON.parse(res);
                var optionsAsString = "<option value=''>---</option>";
                if (res.results !== undefined && res.results.length > 0) {
                    var results = res.results; 
                    for (var i = 0; i < results.length; i++) {
                        optionsAsString += "<option value='" + results[i].id + "' ";
                        optionsAsString += results[i].id == '{$model->sender_id}' ? 'selected' : '';
                        optionsAsString += ">" + results[i].name + "</option>"
                    }
                }
                $(".sender_id select option").each(function() {
                    $(this).remove();
                });
                senderSelect.append( optionsAsString );
                senderSelect.attr('disabled', senderDisable);
            }
        });
    }

    $(".region_id select").change(function() {
        setSender (
            $('#gmsdevices-region_id').val()
        );
    });
    
    $(document).ready(function(){
        setSender (
            $('#gmsdevices-region_id').val()
        );
    });
JS;
$this->registerJs($js1);
