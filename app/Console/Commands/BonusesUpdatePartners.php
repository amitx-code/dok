<?php
/*

Отличная статья:
https://code.tutsplus.com/ru/tutorials/tasks-scheduling-in-laravel--cms-29815

Запус данного файла из крона:
/opt/php73/bin/php /var/www/getlaw/data/www/getlaw.ru/artisan bonuses_partners:update > /var/www/getlaw/data/www/getlaw.ru/logs/BonusesUpdatePartners.log

*/


namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use App\Models\PartnererPartners;
use App\Models\Billing;
use App\Models\BillingTariffs;

class BonusesUpdatePartners extends Command {

	//The name and signature of the console command.
//	protected $signature = 'bonuses:update';
	const BONUS_BILLING_TYPE = 'cashback';

// ВРЕМЕННО ДЛЯ ТЕСТИРОВАНИЯ - ПОТОМ ДЛЯ КРОНА УБРАТЬ ЭТУ СТРОЧКУ И ОСТАВИТЬ ВЕРХНЮЮ
protected $signature = 'bonuses_partners:update {user_id} {partner_id} {partner_cashback} {partner_cashback_type} {summ}';

	//The console command description.
	protected $description = 'Checks and bills the bonuses';

	public function __construct() {
		parent::__construct();
	}

	//############ НАЧИСЛЕНИЕ КЭШБЕКА ОТ ПОКУПОК У ПАРТНЁРОВ/МАГАЗИНОВ  #############
	public function handle() {


// ВРЕМЕННО ДЛЯ ТЕСТИРОВАНИЯ - ПОТОМ УБРАТЬ
$user_id = $this->argument('user_id');
$partner_id = $this->argument('partner_id');
$partner_cashback = $this->argument('partner_cashback');
$partner_cashback_type = $this->argument('partner_cashback_type');
$summ = $this->argument('summ');


//		$user_id = 46; // заменить все это на определение из файла пришедшего из Адмитада
//		$partner_id = 4;
//		$partner_cashback = 15; // цифра процентов или суммы рублей
//		$partner_cashback_type = "%"; // "%" или "руб"
//		$summ = 2000;
		$summ_currency = "RUR"; // пока не запрограммировано

		//############ настроечные массивы всех кэшбэков  #############
		$tariff['STANDARD'][0] = 30; // процентов
		$tariff['STANDARD'][1] = 10;
		$tariff['STANDARD'][2] = 0;
		$tariff['STANDARD'][3] = 0;
		$tariff['STANDARD'][4] = 0;
		$tariff['STANDARD'][5] = 0;
		$tariff['STANDARD'][6] = 0;
		$tariff['STANDARD'][7] = 0;
		$tariff['STANDARD'][8] = 0;
		$tariff['STANDARD'][9] = 0;
		$tariff['GOLD'][0] = 60; // процентов
		$tariff['GOLD'][1] = 20;
		$tariff['GOLD'][2] = 0;
		$tariff['GOLD'][3] = 0;
		$tariff['GOLD'][4] = 0;
		$tariff['GOLD'][5] = 0;
		$tariff['GOLD'][6] = 0;
		$tariff['GOLD'][7] = 0;
		$tariff['GOLD'][8] = 0;
		$tariff['GOLD'][9] = 0;
		$tariff['BUSINESS START'][0] = 60; // процентов
		$tariff['BUSINESS START'][1] = 20;
		$tariff['BUSINESS START'][2] = 10;
		$tariff['BUSINESS START'][3] = 3;
		$tariff['BUSINESS START'][4] = 0;
		$tariff['BUSINESS START'][5] = 0;
		$tariff['BUSINESS START'][6] = 0;
		$tariff['BUSINESS START'][7] = 0;
		$tariff['BUSINESS START'][8] = 0;
		$tariff['BUSINESS START'][9] = 0;
		$tariff['BUSINESS OPTIMUM'][0] = 60; // процентов
		$tariff['BUSINESS OPTIMUM'][1] = 20;
		$tariff['BUSINESS OPTIMUM'][2] = 10;
		$tariff['BUSINESS OPTIMUM'][3] = 3;
		$tariff['BUSINESS OPTIMUM'][4] = 1;
		$tariff['BUSINESS OPTIMUM'][5] = 1;
		$tariff['BUSINESS OPTIMUM'][6] = 0;
		$tariff['BUSINESS OPTIMUM'][7] = 0;
		$tariff['BUSINESS OPTIMUM'][8] = 0;
		$tariff['BUSINESS OPTIMUM'][9] = 0;
		$tariff['BUSINESS МАХ'][0] = 60; // процентов
		$tariff['BUSINESS МАХ'][1] = 20;
		$tariff['BUSINESS МАХ'][2] = 10;
		$tariff['BUSINESS МАХ'][3] = 3;
		$tariff['BUSINESS МАХ'][4] = 1;
		$tariff['BUSINESS МАХ'][5] = 1;
		$tariff['BUSINESS МАХ'][6] = 1;
		$tariff['BUSINESS МАХ'][7] = 1;
		$tariff['BUSINESS МАХ'][8] = 1;
		$tariff['BUSINESS МАХ'][9] = 1;

		print "====================================================================\n";

		//############ ПОЛУЧЕНИЕ ДАННЫХ ПАРТНЕРА  #############
		print "Partner ID " . $partner_id;
		$partner = PartnererPartners::where('id', '=', $partner_id)->first();
		$myPartnerName = $partner->name;
		print ", Name " . $myPartnerName;
		print "\n";

		//############ ПОЛУЧЕНИЕ ДАННЫХ ПОКУПКИ  #############
		print "Summ purshase " . $summ . $summ_currency . ", Partner cashback " . $partner_cashback . $partner_cashback_type . "\n";
		// подсчет базового в реальных деньгах
		if ($partner_cashback_type == "%") {
			$summ_cashback_base = ($summ / 100) * $partner_cashback;
			$summ_cashback_base = round($summ_cashback_base, 2);
			print "Total cashback " . $summ . $summ_currency . " x " . $partner_cashback . $partner_cashback_type . " = " . $summ_cashback_base . $summ_currency;
		} elseif ($partner_cashback_type == "руб") {
			$summ_cashback_base = $partner_cashback;
			$summ_cashback_base = round($summ_cashback_base, 2);
			print "Total cashback " . $summ . $summ_currency . " + " . $partner_cashback . $partner_cashback_type . " = " . $summ_cashback_base . $summ_currency;
		} else {
			$summ_cashback_base = 0;
			print "Total cashback НЕТ";
		}
		print "\n";
		print "-------------------------\n";

		//############ ПОЛУЧЕНИЕ СПИСКА ВЫШЕСТОЯЩИХ РОДИТЕЛЬСКИХ АФИЛАТОВ ОТ ЗАДАННОГО ЮЗЕРА  #############
		$usersLevels = Array();
		$usersTariffs = Array();
		$myAfillate = $user_id;

//		print "Level Srart, User ID: " . $user_id . "\n";

		for ($i=0; $i<=9; $i++) {

			// определяем и записываем в массив id всех вышестоящих юзеров
			$user_id=0;
			$result = User::where('id', '=', $user_id)->first();
			if ($i==0) {
				$user_id = $result->id;
//			} elseif (isset($result->ref_id)) {
			} else {
				$user_id = $result->ref_id;
			}
			if (!isset($user_id)) {continue;}
			if ($user_id == "0") {continue;}
			$usersLevels[$i] = $user_id;
			print "Level ".$i.", User ID " . $user_id;

			// определяем и записываем в массив тариф юзера
			$result = BillingTariffs::where('user_id', '=', $user_id)->first();
			if ($result && $result->tariff) {
				$myTariff = $result->tariff;
				$usersTariffs[$i] = $myTariff;
				print ", Tariff " . $myTariff;
			} else {
				$usersTariffs[$i] = '';
				print ", Tariff ОТСУТСТВУЕТ";
			}

			// определяем физеское начисление процентов
			if (!empty($usersTariffs[$i])) {
				$temp = $usersTariffs[$i];
				$summ_cashback = ($summ_cashback_base / 100) * $tariff[$temp][$i] ;
				print ", Cashback " . $summ_cashback_base . $summ_currency . " x " . $tariff[$temp][$i] . "%" . " = " . $summ_cashback . $summ_currency;
				// фиксация кэшбека юзера ноль как базового кэшбека для дальнейших начислений
				if ($i==0) {
					$summ_cashback_base = $summ_cashback;
				}
			} else {
				$summ_cashback = 0;
				print ", Cashback НЕ ПОЛОЖЕН";
			}

			print "\n";

			// внесение в биллинг начисления
			if ($summ_cashback > 0) {
				if ($i==0) {
					$description = "Начислен кэшбэк по тарифу \"" . $myTariff . "\" от вашей покупки у партнёра \"" . $myPartnerName . "\"";
				} else {
					$description = "Начислен кэшбэк по тарифу \"" . $myTariff . "\" от покупки пользователя ID " . $myAfillate . " (на " . $i . " уровне от вас) у партнёра \"" . $myPartnerName . "\"";
				}

				//Billing::addPayment($user_id, $summ_cashback, $description);

			    $billing = new Billing();
			    $billing->fill([
				  'user_id' => $user_id,
				  'created' => date('Y-m-d H:i:s'),
				  'sum' => $summ_cashback,
				  'description' => $description,
				  'data' => '',
				  'type' => static::BONUS_BILLING_TYPE,
			    ]);
			    $billing->save();

				print "Записано в билинг: " . $description . "\n";
			}
			print "-------------------------\n";

		}

	}
}