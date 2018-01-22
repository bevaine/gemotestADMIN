<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use common\models\Logins;
use common\models\Kontragents;

/* @var $this yii\web\View */
/* @var $model common\models\NWorkshift */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Nworkshifts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$url = \yii\helpers\Url::to(['/admin/logins/ajax-user-data-list']);

$initScript = <<< SCRIPT
    function (element, callback) {
        var id=\$(element).val();
        if (id !== "") {
            \$.ajax("{$url}?id=" + id, {
                dataType: "json"
            }).done(function(data) { callback(data.results);});
        }
    }
SCRIPT;
?>

<div class="nworkshift-view">
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <p>
        <?= Html::a('Создать инкассацию', Url::to(["./encashment/create-encashment", 'workshift_id' => $model->id]), ['class' => 'btn btn-success']) ?>

        <?php
            if (empty($model->close_date)) {
                echo  Html::a('Закрыть смену', ['close', 'id' => $model->id], ['class' => 'btn btn-success']);
            }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'user_aid',
                'value' => function ($model) {
                    /** @var \common\models\NWorkshift $model  */
                    if ($findLogins = Logins::findOne($model->user_aid)) {
                        return $findLogins->Name;
                    } else return null;
                }
            ],
            [
                'attribute' => 'sender_key',
                'value' => function ($model) {
                    /** @var \common\models\NWorkshift $model  */
                    if ($findKontragents = Kontragents::findOne(['Key' => $model->sender_key])) {
                        return $findKontragents->Name;
                    } else return null;
                }
            ],
            [
                'attribute' => 'sender_key_close',
                'value' => function ($model) {
                    /** @var \common\models\NWorkshift $model  */
                    if ($findKontragents = Kontragents::findOne(['Key' => $model->sender_key_close])) {
                        return $findKontragents->Name;
                    } else return null;
                }
            ],
            'kkm',
            'z_num',
            'open_date',
            'close_date',
            'not_zero_sum_start',
            'not_zero_sum_end',
            'amount_cash_register',
            'error_check_count',
            'error_check_total_cash',
            'error_check_total_card',
            'error_check_return_count',
            'error_check_return_total_cash',
            'error_check_return_total_card',
            'file_name',
            'code_1c',
        ],
    ]) ?>

</div>
