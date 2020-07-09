<?php return [

	'project' => [
		'url' => 'https://getlaw.ru',
		'webname' => 'getlaw.ru',
		'email' => 'no-reply@getlaw.ru',

		'title' => 'ДОКУМЕНТЫ ОНЛАЙН', // именительный падеж
		'title2' => 'Портал онлайн-документов GETLAW.ru', // родительный падеж
		'title0' => 'GETLAW', // краткий заголовок в виде названия системы в одно слово
	],

	'logo_title' => [ // лого в админке вверху слева от надпис
		'img' => '/pub_img/logo/logo2.png',
		'width' => '', 
		'height' => '30px',
	],

	'logo_sidebar' => [ // лого в админке слева в сайдбаре под всеми пунктами меню (большое)
		'img' => '', 
		'width' => '150px',
		'height' => '', 
	],

	'logo_login' => [ // лого на странице авторизации-регистрации
		'img' => '',
		'width' => '250px', 
		'height' => '', 
		'img_bg' => '',
		'color_bg' => '#cccccc',
	],


#########################################################################################
	'menu' => [
			(object) [
				'name' => 'Личный кабинет',
				'url' => '/admin',
				'icon' => 'icon-user',
			],

        ####################################
        /*
        (object) [
            'name' => 'Шаблоны документов',
            'code' => 'templater',
            'url' => '#',
            'icon' => 'icon-docs',
            'items' => Array(
                (object) [
                    'name' => 'Заполнить шаблон',
                    'url' => '/admin/templater/add',
                    'icon' => 'fa fa-plus',
                ],
                (object) [
                    'name' => 'История документов',
                    'url' => '/admin/templater/list',
                    'icon' => 'icon-folder',
                ],
            ),
        ],
        */
        (object) [
            'name' => 'Заполнить шаблон',
            'code' => 'templater',
            'url' => '/admin/templater/add',
            'icon' => 'fa fa-plus',
        ],

        ####################################
        /*
        (object) [
            'name' => 'Мои документы',
            'code' => 'documenter',
            'url' => '#',
            'icon' => 'icon-folder',
            'items' => Array(
                (object) [
                    'name' => 'Все документы',
                    'url' => '/admin/documenter/list',
                    'icon' => 'icon-folder',
                ],
            ),
        ],
        */
        (object) [
            'name' => 'Мои документы',
            'code' => 'documenter',
            'url' => '/admin/documenter/list',
            'icon' => 'icon-docs',
        ],

        ####################################
        (object) [
            'name' => "История расходов",
            'url' => '/admin/billing/history',
            'icon' => 'icon-wallet'
        ],

        ####################################
        (object) [
            'name' => "Пополнить баланс",
            'url' => '/admin/billing/pay',
            'icon' => 'icon-credit-card'
        ],

        ####################################
        (object) [
            'name' => "Новости",
            'code' => 'news',
            'url' => '/admin/news',
            'icon' => 'icon-eyeglasses'
        ],

        ####################################
        (object) [
            'name' => "FAQ",
            'code' => 'faq',
            'url' => '/admin/support/faq',
            'icon' => 'icon-question'
        ],

        ####################################
        (object) [
            'name' => "Обучение",
            'code' => 'learning',
            'url' => '/admin/support/learning',
            'icon' => 'icon-book-open'
        ],

        ####################################
        (object) [
            'name' => 'Помощь и поддержка',
            'code' => 'support',
            'url' => '/admin/support/service',
            'icon' => 'icon-earphones',
        ],

        ####################################
        (object) [
            'name' => 'Личные настройки',
            'code' => 'settings',
            'url' => '/admin/settings/personal',
            'icon' => 'icon-user',
        ],




        /*


			####################################
			(object) [
				'name' => 'Покупки и чеки',
				'code' => 'purchaser',
				'url' => '#',
				'icon' => 'icon-docs',
				'items' => Array(
					(object) [
						'name' => 'Все документы',
						'url' => '/admin/purchaser/list',
						'icon' => 'icon-folder',
					],
					(object) [
						'name' => 'Добавить документ',
						'url' => '/admin/purchaser/add',
						'icon' => 'fa fa-plus',
					],
					(object) [
						'name' => 'Поиск документа',
						'url' => '/admin/purchaser/search',
						'icon' => 'icon-magnifier',
					],
					(object) [
						'name' => 'Настройки',
						'url' => '/admin/purchaser/settings',
						'icon' => 'icon-settings',
					],
					(object) [
						'name' => 'Помощь',
						'url' => '/admin/purchaser/help',
						'icon' => 'icon-question',
					],
				),
			],


			####################################
			(object) [
				'name' => "Мои карты",
				'url' => '/admin/billing/cards',
				'icon' => 'icon-credit-card'
			],

			####################################
			(object) [
				'name' => 'Заявки',
				'code' => 'templater',
				'url' => '#',
				'icon' => 'icon-docs',
				'items' => Array(
					(object) [
						'name' => 'Заполнить заявку',
						'url' => '/admin/templater/add',
						'icon' => 'fa fa-plus',
					],
					(object) [
						'name' => 'История заявок',
						'url' => '/admin/templater/list',
						'icon' => 'icon-folder',
					],
					(object) [
						'name' => 'Настройки',
						'url' => '/admin/templater/settings',
						'icon' => 'icon-settings',
					],
					(object) [
						'name' => 'Помощь',
						'url' => '/admin/templater/help',
						'icon' => 'icon-question',
					],
				),
			],



			####################################
			(object) [
				'name' => "Партнёрская программа",
				'code' => 'partnership',
				'url' => '#',
				'icon' => 'icon-link',
				'items' => Array(
					(object) [
						'name' => 'Моя сеть',
						'url' => '/admin/afillater/partnership',
						'icon' => 'icon-link',
					],
					(object) [
						'name' => 'Мой рейтинг',
						'url' => '/admin/rating/history',
						'icon' => 'icon-layers',
					],
				),
			],


			####################################
			(object) [
				'name' => 'Партнёры',
				'code' => 'partnerer',
				'url' => '#',
				'icon' => 'icon-diamond',
				'items' => Array(
					(object) [
						'name' => 'Все партнёры',
						'url' => '/admin/partnerer/list',
						'icon' => 'icon-folder',
					],
					(object) [
						'name' => 'Поиск партнёра',
						'url' => '/admin/partnerer/search',
						'icon' => 'icon-magnifier',
					],
				),
			],



			####################################
			(object) [
				'name' => "Тарифы",
				'code' => 'tariffs',
				'url' => '#',
				'icon' => 'icon-graduation',
				'items' => Array(
					(object) [
						'name' => 'Экономить',
						'url' => '/admin/billing/saving',
						'icon' => 'icon-hourglass',
					],
					(object) [
						'name' => 'Зарабатывать',
						'url' => '/admin/billing/earning',
						'icon' => 'icon-basket-loaded',
					],
				),
			],


			####################################
			(object) [
				'name' => "Услуги",
				'url' => '/admin/services/list',
				'icon' => 'icon-layers'
			],

                    ####################################
                    (object) [
                        'name' => "Документы",
                        'url' => '/admin/support/docs',
                        'icon' => 'icon-printer'
                    ],


                    ####################################
                    (object) [
                        'name' => "Отчёты",
                        'url' => '/admin/reports',
                        'icon' => 'icon-pie-chart'
                    ],


                                    (object) [
                                        'name' => 'Профиль ТСП',
                                        'url' => '/admin/settings/tsp',
                                        'icon' => 'icon-directions',
                                    ],

                            (object) [
                                'name' => "Контакты",
                                'code' => 'contacts',
                                'url' => '/admin/support/contacts',
                                'icon' => 'icon-envelope'
                            ],



                            ####################################
                            (object) [
                                'name' => 'Администрирование',
                                'code' => 'admin',
                                'url' => '#',
                                'icon' => 'icon-settings',
                                'items' => Array(
                                    (object) [
                                        'name' => 'Все сотрудники',
                                        'url' => '/admin/configer/users',
                                        'icon' => 'icon-users',
                                    ],
                                    (object) [
                                        'name' => 'Роли и права',
                                        'url' => '/admin/configer/roles',
                                        'icon' => 'icon-key',
                                    ],
                                    (object) [
                                        'name' => 'API',
                                        'url' => '#',
                                        'icon' => 'icon-cloud-download',
                                        'items' => Array(
                                            (object) [
                                                'name' => 'Авторизация',
                                                'url' => '/admin/configer/token',
                                                'icon' => 'icon-plus',
                                            ],
                //							(object) [
                //								'name' => 'Токены и сайты',
                //								'url' => '/admin/configer/tokens',
                //								'icon' => 'icon-reload',
                //							],
                                            (object) [
                                                'name' => 'ConvertApi',
                                                'url' => '/admin/configer/convertapi',
                                                'icon' => 'icon-game-controller',
                                            ],
                //							(object) [
                //								'name' => 'Помощь',
                //								'url' => '/admin/configer/help',
                //								'icon' => 'icon-question',
                //							],
                                        ),
                                    ],
                                ),
                            ],
                            ####################################

                            (object) [
                                'name' => 'Вернуться на сайт',
                                'code' => 'website',
                                'url' => '/',
                                'icon' => 'icon-action-undo',
                            ],
                */

		],
#########################################################################################

];
