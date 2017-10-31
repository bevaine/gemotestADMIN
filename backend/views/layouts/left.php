<aside class="main-sidebar">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Меню'
                        //,'options' => ['class' => 'sidebar-menu']
                    ],
                    [
                        'label' => 'Гемотест',
                        'icon' => 'share',
                        'url' => '#',
                        //'active' => true,
                        'items' => [
                            ['label' => 'Смены', 'icon' => 'file-code-o', 'url' => ['/admin/workshift'],],
                            ['label' => 'Заказы', 'icon' => 'file-code-o', 'url' => ['/admin/orders'],],
                            ['label' => 'Платежи', 'icon' => 'dashboard', 'url' => ['/admin/pay'],],
                            ['label' => 'Пользователи', 'icon' => 'dashboard', 'url' => ['/admin/logins'],],
                            ['label' => 'Контрагенты', 'icon' => 'dashboard', 'url' => ['/admin/kontragents'],],
                            ['label' => 'Движение ДС в ЛО', 'icon' => 'dashboard', 'url' => ['/admin/cash-balance-lo'],],
                            ['label' => 'Таблицы', 'icon' => 'circle-o', 'url' => '#', 'items' =>
                                [
                                    [
                                        'label' => 'NAdUsers',
                                        'active' => true,
                                        'icon' => 'dashboard',
                                        'url' => ['/admin/n-ad-users'],
                                    ],
                                ]
                            ],
                            ['label' => 'Операции', 'icon' => 'circle-o', 'url' => '#', 'items' =>
                                [
                                    ['label' => 'Сбой инхронизации', 'active' => true, 'icon' => 'dashboard', 'url' => 'http://labc:57772/csp/syncutils/sumain.csp',],
                                ]
                            ],
                        ],
                    ],
                    [
                        'label' => 'Служебные',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                            [
                                'label' => 'Пользователи',
                                'active' => Yii::$app->user->can('administrator'),
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Пользователи', 'icon' => 'circle-o', 'url' => \yii\helpers\Url::to(['/user/admin']),],
                                    ['label' => 'Роли/права', 'icon' => 'circle-o', 'url' => \yii\helpers\Url::to(['/user/rbac']),],
                                ],
                            ],
                        ]
                    ]
                ],
            ]
        ) ?>

    </section>

</aside>
