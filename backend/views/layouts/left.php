<aside class="main-sidebar">

    <section class="sidebar">
        <? //print_r(Yii::$app->user); ?>

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
                        'visible' => Yii::$app->user->can('GemotestAdmin'),
                    ],
                    [
                        'label' => 'Модуль GMS',
                        'icon' => 'share',
                        'url' => '#',
                        'active' => true,
                        'visible' => Yii::$app->user->can('GMSaccess'),
                        'items' => [
                            ['label' => 'Видео', 'icon' => 'file-code-o', 'url' => ['/GMS/gms-videos']],
                            ['label' => 'Устройства', 'icon' => 'file-code-o', 'url' => ['/GMS/gms-devices']],
                            ['label' => 'Шаблоны', 'icon' => 'file-code-o', 'url' => ['/GMS/playlist']],
                            ['label' => 'Плейлисты', 'icon' => 'file-code-o', 'url' => ['/GMS/playlist-out']],
                            ['label' => 'Отчеты',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'active' => true,
                                'items' => [
                                    ['label' => 'Видео', 'icon' => 'dashboard', 'url' => '/GMS/reports/video'],
                                ]
                            ],
                            ['label' => 'История',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'active' => true,
                                'items' => [
                                    ['label' => 'Плейлисты', 'icon' => 'dashboard', 'url' => '/GMS/gms-history'],
                                    //['label' => 'Видео', 'icon' => 'dashboard', 'url' => '/GMS/gms-video-history'],
                                ]
                            ],
                            [
                                'label' => 'Справочники',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'active' => true,
                                'items' => [
                                    ['label' => 'Регионы', 'icon' => 'dashboard', 'url' => '/GMS/gms-regions'],
                                    ['label' => 'Отделения', 'icon' => 'dashboard', 'url' => '/GMS/gms-senders'],
                                    ['label' => 'Группы устройств', 'icon' => 'dashboard', 'url' => '/GMS/gms-group-devices'],
                                ]
                            ]
                        ]
                    ],
                    [
                        'label' => 'Гемотест',
                        'icon' => 'share',
                        'url' => '#',
                        'visible' => Yii::$app->user->can('GemotestAdmin'),
                        'items' => [
                            ['label' => 'Заказы', 'icon' => 'circle-o', 'url' => '#', 'active' => true,
                                'items' =>[
                                    [
                                        'label' => 'Лабораторные',
                                        'icon' => 'dashboard',
                                        'active' => true,
                                        'url' => '#',
                                        'items' => [
                                            [
                                                'label' => 'Заказы',
                                                'icon' => 'dashboard',
                                                'url' => ['/admin/orders'],
                                            ],
                                            [
                                                'label' => 'Взятие БМ',
                                                'icon' => 'dashboard',
                                                'url' => ['/admin/input-order-zabor'],
                                            ],
                                        ]
                                    ],
                                    [
                                        'label' => 'Медицинские',
                                        'icon' => 'dashboard',
                                        'active' => true,
                                        'url' => '#',
                                        'items' => [
                                            [
                                                'label' => 'Заказы',
                                                'icon' => 'dashboard',
                                                'url' => ['/admin/med-order'],
                                            ],
                                            [
                                                'label' => 'Врачи вып. исслед.',
                                                'icon' => 'dashboard',
                                                'url' => ['/admin/med-appointment'],
                                            ],
                                        ]
                                    ],
                                ]
                            ],
                            ['label' => 'Смены', 'active' => true, 'icon' => 'circle-o', 'url' => '#', 'items' =>
                                [
                                    [
                                        'label' => 'Смены',
                                        'icon' => 'dashboard',
                                        'url' => ['/admin/workshift'],
                                    ],
                                    [
                                        'label' => 'Регистрация в смене',
                                        'icon' => 'dashboard',
                                        'url' => ['/admin/branch-staff'],
                                    ],

                                ]
                            ],
                            ['label' => 'Платежи', 'icon' => 'circle-o', 'url' => '#', 'active' => true,
                                'items' =>[
                                    [
                                        'label' => 'Лабораторные',
                                        'icon' => 'dashboard',
                                        'url' => ['/admin/pay'],
                                    ],
                                    [
                                        'label' => 'Медицинские',
                                        'icon' => 'dashboard',
                                        'url' => ['/admin/med-pay'],
                                    ],
                                ]
                            ],
                            ['label' => 'Возвраты', 'icon' => 'circle-o', 'url' => '#',
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
                            [
                                'label' => 'ККМ',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'active' => true,
                                'items' => [
                                    ['label' => 'Пользователи ККМ', 'icon' => 'dashboard', 'url' => '/admin/kkm-users/'],
                                ]
                            ],
                            ['label' => 'Пользователи', 'icon' => 'dashboard', 'url' => ['/admin/logins'],],
                            ['label' => 'Инкасации', 'icon' => 'dashboard', 'url' => ['/admin/encashment'],],
                            ['label' => 'Контрагенты', 'icon' => 'dashboard', 'url' => ['/admin/kontragents'],],
                            ['label' => 'Франчайзи', 'active' => true, 'icon' => 'circle-o', 'url' => '#', 'items' =>
                                [
                                    [
                                        'label' => 'Отделения',
                                        'icon' => 'dashboard',
                                        'url' => ['/admin/franchazy'],
                                    ],
                                    [
                                        'label' => 'Мед. сообщество',
                                        'icon' => 'dashboard',
                                        'url' => ['/admin/rep-period-lab-gemotest'],
                                    ],
                                ]
                            ],
                            ['label' => 'Запись на прием', 'active' => true, 'icon' => 'circle-o', 'url' => '#', 'items' =>
                                [
                                    [
                                        'label' => 'Отделения',
                                        'icon' => 'dashboard',
                                        'url' => ['/admin/spr-filials'],
                                    ],
                                    [
                                        'label' => 'Запись на прием',
                                        'icon' => 'dashboard',
                                        'url' => ['/admin/doctor-spec'],
                                    ]
                                ],
                            ],
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
                                    ['label' => 'Отчет о доставке СМС', 'active' => true,'icon' => 'dashboard', 'url' => '/admin/sms',],
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
                        'visible' => Yii::$app->user->can('userManage'),
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
