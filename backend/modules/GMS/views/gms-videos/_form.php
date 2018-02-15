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
?>
<div class="gms-videos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <?= Html::hiddenInput('GmsVideos[fileName]', null , ['id' => 'gmsvideos-fileName'])?>

    <?= FileUploadUI::widget([
        'model' => $model,
        'attribute' => 'file',
        'url' => ['gms-videos/video-upload'], // your url, this is just for demo purposes,
        'options' => ['accept' => 'video/*'],
        'gallery' => true,
        'clientOptions' => [
            'maxNumberOfFiles' => 1,
        ],
        'clientEvents' => [
            'fileuploaddone' => "function (e, data) {
                var url = data.result.files[0].url; 
                $(\"#gmsvideos-fileName\").val(url);
            }",
            'fileuploadfail' => "function(e, data) {

            }",
        ],
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
