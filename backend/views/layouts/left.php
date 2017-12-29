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
                        'label' => 'Wiki',
                        'icon' => 'dashboard',
                        'url' => ['/wiki/content/admin'],
                    ],
                    [
                        'label' => 'Гемотест',
                        'icon' => 'share',
                        'url' => '#',
                        //'active' => true,
                        'items' => [
                            ['label' => 'Смены', 'active' => true, 'icon' => 'circle-o', 'url' => '#', 'items' =>
                                [
                                    [
                                        'label' => 'Смены',
                                        'icon' => 'dashboard',
                                        'url' => ['/admin/workshift'],
                                    ],
                                    [
                                        'label' => 'Доб. в смене',
                                        'icon' => 'dashboard',
                                        'url' => ['/admin/branch-staff'],
                                    ],
                                    [
                                        'label' => 'Взятие БМ',
                                        'icon' => 'dashboard',
                                        'url' => ['/admin/input-order-zabor'],
                                    ]
                                ]
                            ],
                            [
                                'label' => 'Возвраты',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    [
                                        'label' => 'Лабораторные',
                                        'active' => true,
                                        'icon' => 'dashboard',
                                        'url' => [''],
                                        'items' => [
                                            [
                                                'label' => 'Возвраты',
                                                'icon' => 'dashboard',
                                                'url' => ['/admin/n-return-order'],
                                            ],
                                            [
                                                'label' => 'Без номенклатуры',
                                                'icon' => 'dashboard',
                                                'url' => ['/admin/n-return-without-item'],
                                            ],
                                        ]
                                    ],
                                    [
                                        'label' => 'Медицинские',
                                        'active' => true,
                                        'icon' => 'dashboard',
                                        'url' => [''],
                                        'items' => [
                                            [
                                                'label' => 'Возвраты',
                                                'icon' => 'dashboard',
                                                'url' => ['/admin/med-return-order'],
                                            ],
                                            [
                                                'label' => 'Без номенклатуры',
                                                'icon' => 'dashboard',
                                                'url' => ['/admin/med-return-without-item'],
                                            ],
                                        ]
                                    ],
                                ]
                            ],
                            ['label' => 'Инкасации', 'icon' => 'dashboard', 'url' => ['/admin/encashment'],],
                            ['label' => 'Заказы', 'icon' => 'dashboard', 'url' => ['/admin/orders'],],
                            ['label' => 'Платежи', 'icon' => 'dashboard', 'url' => ['/admin/pay'],],
                            ['label' => 'Пользователи', 'icon' => 'dashboard', 'url' => ['/admin/logins'],],
                            ['label' => 'Контрагенты', 'icon' => 'dashboard', 'url' => ['/admin/kontragents'],],
                            ['label' => 'Движение ДС в ЛО', 'icon' => 'dashboard', 'url' => ['/admin/cash-balance-lo'],],
                            ['label' => 'Таблицы', 'active' => true, 'icon' => 'circle-o', 'url' => '#', 'items' =>
                                [
                                    [
                                        'label' => 'NAdUsers',

                                        'icon' => 'dashboard',
                                        'url' => ['/admin/n-ad-users'],
                                    ],
                                ]
                            ],
                            ['label' => 'Операции', 'icon' => 'circle-o', 'url' => '#', 'items' =>
                                [
                                    ['label' => 'Сбой инхронизации', 'active' => true,'icon' => 'dashboard', 'url' => 'http://labc:57772/csp/syncutils/sumain.csp',],
                                ]
                            ],
                        ],
                    ],
                    [
                        'label' => 'Служебные',
                        'icon' => 'share',
                        'url' => '#',
                        'active' => true,
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                            [
                                'label' => 'Пользователи',
                                'active' => true,
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
