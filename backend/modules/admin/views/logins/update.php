<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\LoginsSearch */
/* @var $ad integer */

$this->title = 'Редактирование: ' . $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->Name, 'url' => ['view', 'id' => $model->aid, 'ad' => $ad]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="logins-update">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'aid')->textInput(['readonly' => true]) ?>

    <?php
    if ($model->adUsersOne) {
        echo $form->field($model->adUsersOne, 'last_name')->textInput();
        echo $form->field($model->adUsersOne, 'first_name')->textInput();
        echo $form->field($model->adUsersOne, 'middle_name')->textInput();
        echo $form->field($model->adUsersOne, 'AD_position')->textInput();
    } ?>

    <?php
    if ($model->adUserAccountsOne) {
        echo $form->field($model->adUserAccountsOne, 'ad_login')->textInput(['readonly' => true]);
        echo $form->field($model->adUserAccountsOne, 'ad_pass')->textInput(['readonly' => true]);
    } ?>

    <?= $form->field($model, 'Login')->textInput(['readonly' => true]) ?>

    <?= $form->field($model, 'Pass')->textInput(['readonly' => true]) ?>

    <?= $form->field($model, 'Name')->textInput() ?>

    <?= $form->field($model, 'Email')->textInput() ?>

    <?= $form->field($model, 'Key')->textInput() ?>

    <?= $form->field($model, 'Logo')->textInput() ?>

    <?= $form->field($model, 'LogoText')->textInput() ?>

    <?= $form->field($model, 'LogoText2')->textInput() ?>

    <?= $form->field($model, 'UserType')->dropDownList(\common\models\Logins::getTypesArray()); ?>

    <?= $form->field($model, 'CACHE_Login')->textInput(['readonly' => true]) ?>

    <?= $form->field($model, 'LastLogin')->textInput([
        'readonly' => true,
        'value' => date("Y-m-d G:i:s", strtotime($model->LastLogin))
    ]) ?>

    <?php
        echo '<p><label class="control-label">Дата блокировки</label>';
        echo DateTimePicker::widget([
            'name' => 'Logins[DateEnd]',
            'type' => DateTimePicker::TYPE_INPUT,
            'value' => !empty($model->DateEnd) ? date("Y-m-d G:i:s", strtotime($model->DateEnd)) : '',
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd hh:ii:ss'
            ]
        ]);
        echo '</p><p><label class="control-label">Дата запрета рег.</label>';
        echo DateTimePicker::widget([
            'name' => 'Logins[block_register]',
            'type' => DateTimePicker::TYPE_INPUT,
            'value' => !empty($model->block_register) ? date("Y-m-d G:i:s", strtotime($model->block_register)) : '',
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd hh:ii:ss'
            ]
        ]);
        echo '</p>';
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
