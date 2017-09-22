<?php
use kartik\icons\Icon;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Меню', 'options' => ['class' => 'header']],
                    [
                        'label' => 'Гемотест',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Смены', 'icon' => 'file-code-o', 'url' => ['/admin/workshift'],],
                            ['label' => 'Заказы', 'icon' => 'file-code-o', 'url' => ['/admin/orders'],],
                            ['label' => 'Платежи', 'icon' => 'dashboard', 'url' => ['/admin/pay'],],
                            ['label' => 'Пользователи', 'icon' => 'dashboard', 'url' => ['/admin/logins'],],
                            ['label' => 'Контрагенты', 'icon' => 'dashboard', 'url' => ['/admin/kontragents'],],
                            ['label' => 'Движение ДС в ЛО', 'icon' => 'dashboard', 'url' => ['/admin/cash-balance-lo'],],
                            ['label' => 'Таблицы', 'icon' => 'circle-o', 'url' => '#', 'items' =>
                                [
                                    ['label' => 'NAdUsers', 'icon' => 'dashboard', 'url' => ['/admin/logins/create-n-ad-users'],],
                                ]
                            ],
                            ['label' => 'Операции', 'icon' => 'circle-o', 'url' => '#', 'items' =>
                                [
                                    ['label' => 'Сбой инхронизации', 'icon' => 'dashboard', 'url' => 'http://labc:57772/csp/syncutils/sumain.csp',],
                                ]
                            ],
//                            [
//                                'label' => 'Level One',
//                                'icon' => 'circle-o',
//                                'url' => '#',
//                                'items' => [
//                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
//                                    [
//                                        'label' => 'Level Two',
//                                        'icon' => 'circle-o',
//                                        'url' => '#',
//                                        'items' => [
//                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
//                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
//                                        ],
//                                    ],
//                                ],
//                            ],
                        ],
                    ],
                    [
                        'label' => 'Служебные',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                        ]
                    ]
                ],
            ]
        ) ?>

    </section>

</aside>
