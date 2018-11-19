<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Sms */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sms-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'status',
            'client_id',
            'phone',
            'message',
            'tz',
            'priority',
            'enqueued:boolean',
            'attempt',
            'provider_id',
            'provider_sms_id',
            'deliver_sm:ntext',
            'bounce_reason',
            'created_at',
            'updated_at',
            'callback',
            'attempts_get_status',
        ],
    ]) ?>

</div>
