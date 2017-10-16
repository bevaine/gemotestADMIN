<?php
use yii\helpers\Html;
use budyaga\users\models\User;
use budyaga\users\UsersAsset;

/* @var $this \yii\web\View */
/* @var $content string */
/* @var User UserModel */

$userModel = User::findOne(Yii::$app->user->id);
$assets = UsersAsset::register($this);
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <li class="dropdown messages-menu"></li>

                <li class="dropdown notifications-menu"></li>

                <li class="dropdown tasks-menu"></li>

                <li class="dropdown user user-menu">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?= html::img(($userModel->photo) ? $userModel->photo : $assets->baseUrl . '/img/' . $userModel->getDefaultPhoto() . '.png',                        [
                                'style' => 'max-height: 50px; max-width: 50px',
                                'class' => 'user-image']
                        ); ?>
                        <span class="hidden-xs"><?= $userModel->username?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <?= html::img(($userModel->photo) ? $userModel->photo : $assets->baseUrl . '/img/' . $userModel->getDefaultPhoto() . '.png',                        [
                                    'class' => 'img-circle']
                            ); ?>
                            <p>
                                <?= $userModel->username?>
                                <small>Member since: <?= date("F j, Y", $userModel->created_at) ?> </small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a(
                                    'Профиль',
                                    ['/profile'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Выйти',
                                    ['/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>