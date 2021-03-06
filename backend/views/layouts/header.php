<?php
use yii\helpers\Html;
use budyaga\users\models\User;
use budyaga\users\UsersAsset;

/* @var $this \yii\web\View */
/* @var $content string */
/* @var User UserModel */

$assets = UsersAsset::register($this);
$userModel = User::findOne(Yii::$app->user->id);
$nameApp = '<span class="logo-lg">' . Yii::$app->name . '</span>';
if (Yii::$app->user->can('GMSaccess')
    && !Yii::$app->user->can('GemotestAdmin')) {
    $nameApp = html::img( '/img/logo_main.jpg');
}
?>
<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . $nameApp . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <li class="dropdown messages-menu"></li>

                <?php if (Yii::$app->user->can('GemotestAdmin')) : ?>
                    <li class="dropdown notifications-menu" id="log-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-warning"></i>
                            <span class="label label-danger">
                                <?php echo \common\models\SystemLog::find()->count() ?>
                            </span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header"><?= 'Всего записей ' . \common\models\SystemLog::find()->count(). ' шт.' ?></li>
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    <?php foreach (\common\models\SystemLog::find()->orderBy(['log_time' => SORT_DESC])->limit(5)->all() as $logEntry): ?>
                                        <li>
                                            <a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/system-log/view', 'id' => $logEntry->id]) ?>">
                                                <i class="fa fa-warning <?php echo $logEntry->level == \yii\log\Logger::LEVEL_ERROR ? 'text-red' : 'text-yellow' ?>"></i>
                                                <?php echo $logEntry->category ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                            <li class="footer">
                                <?php echo Html::a('Посмотреть все', ['/admin/system-log/index']) ?>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

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
