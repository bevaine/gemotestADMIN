<?
use dmstr\widgets\Menu;
?>

<aside class="main-sidebar">

    <section class="sidebar">

        <?= Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Меню', 'options' => ['class' => 'header']],
                    //[
                        //'label' => 'Отчеты',
                        //'icon' => 'share',
                        //'url' => '#',
                        //'items' => [
                            ['label' => 'Отчет по перевесам', 'icon' => 'file-code-o', 'url' => ['/report/doctor-report'],],
                       // ],
                    ],
                //],
            ]
        ) ?>

    </section>

</aside>
