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

        if (file.preview !== undefined && file.name !== undefined && file.size !== undefined) {
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
        ],
    ]); ?>
    <?php ActiveForm::end(); ?>

</div>
<?php
$js = <<< JS
var entityMap = {
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
    var base = new String(str).substring(str.lastIndexOf('/') + 1);
    if(base.lastIndexOf(".") != -1)
        base = base.substring(0, base.lastIndexOf("."));
    return base;
}
JS;
$this->registerJs($js);
?>