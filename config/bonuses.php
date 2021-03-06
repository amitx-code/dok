<?php return [


//############ БАЛЛЫ БОНУСОВ-ПРОЦЕНТОВ ЗА КУПЛЕННЫЕ ТАРИФЫ #############
	'bonuses_tariffs' => [
		'STANDARD' => [
		 	'1' => 10,
			'2' => 0, 
			'3' => 0, 
			'4' => 0, 
			'5' => 0, 
			'6' => 0, 
			'7' => 0, 
			'8' => 0, 
		], 
		'GOLD' => [
		 	'1' => 10,
			'2' => 0, 
			'3' => 0, 
			'4' => 0, 
			'5' => 0, 
			'6' => 0, 
			'7' => 0, 
			'8' => 0, 
		], 
		'BUSINESS START' => [
		 	'1' => 10,
			'2' => 9, 
			'3' => 8, 
			'4' => 0,
			'5' => 0,
			'6' => 0, 
			'7' => 0, 
			'8' => 0, 
		], 
		'BUSINESS OPTIMUM' => [
		 	'1' => 10,
			'2' => 9, 
			'3' => 8, 
			'4' => 7, 
			'5' => 6, 
			'6' => 0, 
			'7' => 0, 
			'8' => 0, 
		], 
		'BUSINESS MAX' => [
		 	'1' => 10,
			'2' => 9, 
			'3' => 8, 
			'4' => 7, 
			'5' => 6, 
			'6' => 5, 
			'7' => 4, 
			'8' => 3, 
		], 
	],




//############ БАЛЛЫ РЕЙТИНГА ОТ КУПЛЕННЫХ ТАРИФОВ #############
	'rating_tariffs' => [
		'STANDARD' => [
		 	'1' => 0,
			'2' => 0, 
			'3' => 0, 
			'4' => 0, 
			'5' => 0, 
			'6' => 0, 
			'7' => 0, 
			'8' => 0, 
		], 
		'GOLD' => [
		 	'1' => 0.1,
			'2' => 0.1, 
			'3' => 0.1, 
			'4' => 0.1, 
			'5' => 0.1, 
			'6' => 0.1, 
			'7' => 0.1, 
			'8' => 0.1, 
		], 
		'BUSINESS START' => [
		 	'1' => 1.5,
			'2' => 1.5, 
			'3' => 1.5, 
			'4' => 1.5, 
			'5' => 1.5, 
			'6' => 1.5, 
			'7' => 1.5, 
			'8' => 1.5, 
		], 
		'BUSINESS OPTIMUM' => [
		 	'1' => 15,
			'2' => 15, 
			'3' => 15, 
			'4' => 15, 
			'5' => 15, 
			'6' => 15, 
			'7' => 15, 
			'8' => 15, 
		], 
		'BUSINESS MAX' => [
		 	'1' => 50,
			'2' => 50, 
			'3' => 50, 
			'4' => 50, 
			'5' => 50, 
			'6' => 50, 
			'7' => 50, 
			'8' => 50, 
		], 
	],




//############ БАЛЛЫ БОНУС-БОКС ЗА НАБРАННЫЕ ПАКЕТЫ БИЗНЕС-ТАРИФОВ #############
	'bonuses_box' => 
	[
		'BUSINESS OPTIMUM' => 
		[
		 	'1' => [
				'packs' => 4,
				'percent' => 10,
			],
		 	'2' => [
				'packs' => 8,
				'percent' => 9,
			],
		 	'3' => [
				'packs' => 16,
				'percent' => 8,
			],
		 	'4' => [
				'packs' => 32,
				'percent' => 7,
			],
		 	'5' => [
				'packs' => 64,
				'percent' => 6,
			],
		], 
		'BUSINESS MAX' => 
		[
		 	'1' => [
				'packs' => 4,
				'percent' => 10,
			],
		 	'2' => [
				'packs' => 8,
				'percent' => 9,
			],
		 	'3' => [
				'packs' => 16,
				'percent' => 8,
			],
		 	'4' => [
				'packs' => 32,
				'percent' => 7,
			],
		 	'5' => [
				'packs' => 64,
				'percent' => 6,
			],
		 	'6' => [
				'packs' => 128,
				'percent' => 5,
			],
		 	'7' => [
				'packs' => 256,
				'percent' => 4,
			],
		 	'8' => [
				'packs' => 512,
				'percent' => 3,
			],
		], 
	],



];
