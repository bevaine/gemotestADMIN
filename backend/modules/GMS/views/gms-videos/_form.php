<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use xutl\videojs\VideoJsWidget;
use yii\helpers\FileHelper;
use common\components\helpers\FunctionsHelper;
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

    <?= Html::hiddenInput('GmsVideos[fileName]', null , ['id' => 'gmsvideos-fileName'])?>

    <?= FileUploadUI::widget([
        'model' => $model,
        'attribute' => 'file',
        'url' => ['gms-videos/video-upload'], // your url, this is just for demo purposes,
        'options' => ['accept' => 'video/*'],
        'gallery' => true,
        'clientOptions' => [
            'maxNumberOfFiles' => 8,
        ],
        'clientEvents' => [
            'fileuploadprogressall' => 'function (e, data) {
                //console.log(data); 
            }',
            'fileuploadprocessalways' => $htmlFileUpload,
            'fileuploaddone' => 'function (e, data) {
                
                var url = data.result.files[0].url; 
                $("#gmsvideos-fileName").val(url);
            }',
            'fileuploadfail' => "function(e, data) {

            }",
        ],
    ]); ?>
    <?php ActiveForm::end(); ?>

</div>
<?php
$js1 = <<< JS

    function addFields() {
        
    }
JS;

$this->registerJs($js1);

