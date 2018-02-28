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
            
        var fileName = btoa(file.name);    

        if (file.preview) {   
            file.preview.width = 750;     
            var html_text = "<div class='row'><div class='col-lg-10'>";
            html_text += "<label for='{class:control-label}'>Название</label>"
            html_text += "<p><input type='text' class='form-control' name='GmsVideos[" + fileName + "][name]'></p>";
            html_text += "<label for='{class:control-label}'>Комментарий</label>";
            html_text += "<p><textarea class='form-control' name='GmsVideos[" + fileName + "][comment]' rows='6'></textarea></p>";
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
            'fileuploaddone' => 'function (e, data) {
            }',
            'fileuploadfail' => "function(e, data) {
            }",
        ],
    ]); ?>
    <?php ActiveForm::end(); ?>

</div>