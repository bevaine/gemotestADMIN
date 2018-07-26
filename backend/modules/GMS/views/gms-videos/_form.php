<?php
use yii\widgets\ActiveForm;
use dosamigos\fileupload\FileUploadUI;

/* @var $this yii\web\View */
/* @var $model common\models\GmsVideos */
/* @var $form yii\widgets\ActiveForm */

$htmlFileUpload = <<<HTML
    function(e, data) {
        var index = data.index,
            file = data.files[index],
            node = $(data.context.children()[index]);

        if (file.preview !== undefined 
            && file.name !== undefined 
            && file.size !== undefined) {
            var fileName = baseName(file.name),  
            txt_val = escapeHtml(fileName),
            fileSize = file.size;            
            file.preview.width = 720;     
            var html_text = "<div class='row'><div class='col-lg-10'>";
            html_text += "<label for='{class:control-label}'>Название</label>"
            html_text += "<p><input style='width: 720px' type='text' value='" + txt_val + "' class='form-control' name='GmsVideos[" + fileSize + "][name]'></p>";
            html_text += "<label for='{class:control-label}'>Комментарий</label>";
            html_text += "<p><textarea style='width: 720px' class='form-control' name='GmsVideos[" + fileSize + "][comment]' rows='6'></textarea></p>";
            html_text += "</div></div>"
            node
                .prepend("<br>")
                .prepend(html_text)
                .prepend(file.preview);
        }
    }
HTML;
?>
<div class="modal bootstrap-dialog type-warning fade size-normal in" id="modal-dialog" tabindex="-1" role="dialog" aria-labelledby="deactivateLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="bootstrap-dialog-header">
                    <div class="bootstrap-dialog-close-button" style="display: none;">
                        <button class="close" aria-label="close">×</button>
                    </div>
                    <div class="bootstrap-dialog-title" id="w1_title">Подтверждение</div>
                </div>
            </div>
            <div class="modal-body">
                <div class="bootstrap-dialog-body">
                    <div class="bootstrap-dialog-message" id="bootstrap-dialog-message">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="bootstrap-dialog-footer">
                    <div class="bootstrap-dialog-footer-buttons" id="bootstrap-dialog-footer-buttons"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="gms-videos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= FileUploadUI::widget([
        'model' => $model,
        'attribute' => 'file',
        'url' => [
            'gms-videos/video-upload'
        ],
        'options' => [
            'accept' => 'video/*'
        ],
        'gallery' => true,
        'clientOptions' => [
            'maxNumberOfFiles' => 8,
        ],
        'clientEvents' => [
            'fileuploadprocessalways' => $htmlFileUpload,
            'fileuploadprogressall' => 'function (e, data) {
            }',
            'fileuploaddone' => 'function(e, data) {
            }',
            'fileuploadfail' => "function(e, data) {
            }",
            'fileuploadadd' => 'function(e, data) {
                window.setTimeout(function() {
                    let width = 0, height = 0;
                    if (data.files[0].preview !== undefined) {
                        width = data.files[0].preview.videoWidth;
                        height = data.files[0].preview.videoHeight;                        
                    }
                    if (width === undefined 
                        || height === undefined 
                        || width === 0
                        || height === 0) {
                        return;
                    }
                    if (width > 1280 || height > 720) {
                        data.context["0"].innerHTML = "";
                        let message = "Размеры <b>" + width + "x" + height + "</b> данного видео не соотвествуют максимально установленным <b>1280x720</b>";
                        let style = "danger";
                        let button_cancel = "<button class=\'btn btn-default\' data-dismiss=\'modal\'>";
                            button_cancel += "<span class=\'glyphicon glyphicon-ban-circle\'></span> Отмена";
                            button_cancel += "</button>";
                        $("#bootstrap-dialog-message").html(message);  
                        $("#bootstrap-dialog-footer-buttons").html(button_cancel); 
                        $("#modal-dialog")
                            .removeClass()
                            .toggleClass("modal bootstrap-dialog type-" + style + " fade size-normal in")
                            .modal("show");
                    }
                }, 500);
            }',
        ],
    ]); ?>
    <?php ActiveForm::end(); ?>

</div>
<?php
$js = <<< JS
    let entityMap = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;',
      '/': '&#x2F;',
      '`': '&#x60;',
      '=': '&#x3D;'
};

function escapeHtml(string) 
{
    return String(string).replace(/[&<>"'`=\/]/g, function (s) {
        return entityMap[s];
    });
}

function baseName(str)
{
    let base = String(str).substring(str.lastIndexOf('/') + 1);
    if (base.lastIndexOf(".") !== -1)
        base = base.substring(0, base.lastIndexOf("."));
    return base;
}
JS;
$this->registerJs($js);
?>