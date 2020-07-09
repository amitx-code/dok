<?php return [

	'login' => env('ROBOKASSA_LOGIN', 'getlaw'),
//	'password1' => env('ROBOKASSA_PASSWORD1', 'S16omKlrq1lYgF1aMX5o'),
//	'password2' => env('ROBOKASSA_PASSWORD2', 'wXhNDJ1CXd70I1j0JiyF'),
	'endpoint' => env('ROBOKASSA_ENDPOINT', 'https://merchant.roboxchange.com/Index.aspx'),

	// включить для тестов, выключить при боевом режиме
	'password1' => env('ROBOKASSA_PASSWORD1', 'SDvMYaiESr3x5tV94S7d'), // тестовый пароль
	'password2' => env('ROBOKASSA_PASSWORD2', 'AiUN3k2X4HReYYWt54oc'), // тестовый пароль
	'IsTest' => env('ROBOKASSA_ISTEST', 1),
];
