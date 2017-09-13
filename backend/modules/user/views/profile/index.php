<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \common\models\User */

$this->title = Yii::t('app', 'Профиль');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-profile">

    <p>
        <?= Html::a(Yii::t('app', 'Обновить'), ['update'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Сменить пароль'), ['password-change'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'email',
        ],
    ]) ?>

</div>

