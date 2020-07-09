<?php

namespace App\Models;

use App\Exceptions\BillingException;
use App\Libs\Helpers;
use App\Libs\Robokassa;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Billing extends CachedModel {
        protected $table = 'billing';
        public $timestamps = false;
        protected $guarded = array('id');




        protected static function getBalanceCacheKey($userId) {
            return 'user:' . $userId . ':balance';
        }
        protected static function getBalanceCacheKeyAdd($userId, $addkey) {
            return 'user:' . $userId . ':balance' . $addkey;
        }
        protected static function getCacheKey($addkey) {
            return 'billing:' . $addkey;
        }




	//####################### получение на текущий момент ##############################
        public static function getBalance($userId, $clearCache = false) {
            if ($clearCache) {
                Cache::forget(static::getBalanceCacheKey($userId));
            }

            return Cache::remember(static::getBalanceCacheKey($userId), 60, function() use ($userId) {
                $myIncome = static::where('user_id', $userId)->sum('sum') - 0;
                return $myIncome;
            });
        }




	//################## получение баланса за месяц #########################
	public static function getBalanceMonth($userId, $monthFromHere = 0, $clearCache = false) {
		if ($clearCache) {
			Cache::forget(static::getBalanceCacheKeyAdd($userId, "month:".$monthFromHere));
		}

		$year = date('Y');
		$month = date('m') - $monthFromHere;
		if ($month == 0) {$year--; $month=12;}
		elseif ($month < 0) {$year--; $month=12+$month;}
		$month = str_pad($month, 2, '0', STR_PAD_LEFT);

		$dateStart = $year . "-" . $month . "-01 00:00:00"   ;
		$dateEnd = $year . "-" . $month . "-" . date('t') . " 23:59:59"   ;

		return Cache::remember(static::getBalanceCacheKeyAdd($userId, "month:".$monthFromHere), 60, function() use ($userId, $dateStart, $dateEnd, $month) {
			$result = (object)Array();
			$result->month = $month * 1;

			$total1 = static::where('user_id', $userId)
				->where('type', 'bonus_tariff')
				->where('created', '>=', $dateStart)
				->where('created', '<=', $dateEnd)
				->sum('sum') - 0;
			$result->total1 = $total1;

			$total2 = static::where('user_id', $userId)
				->where('type', 'cashback')
				->where('created', '>=', $dateStart)
				->where('created', '<=', $dateEnd)
				->sum('sum') - 0;
			$result->total2 = $total2;

			$result->total = $total1 + $total2;
			return $result;
		});
	}








	//############### получение массива балансов за N месяцев ################
	public static function getBalanceMonths($userId, $monthFromHereStart = 0, $monthFromHereEnd = 0, $clearCache = false) {

		if ($monthFromHereStart > $monthFromHereEnd) {
			$start = $monthFromHereEnd;
			$end = $monthFromHereStart;
			$way = 0;
		} else {
			$start = $monthFromHereStart;
			$end = $monthFromHereEnd;
			$way = 1;
		}

		if ($clearCache) {
			Cache::forget(static::getBalanceCacheKeyAdd($userId, "month:".$start.":".$end));
		}

		return Cache::remember(static::getBalanceCacheKeyAdd($userId, "month:".$start.":".$end), 60, function() use ($userId, $start, $end, $way, $clearCache) {
			$stats = Array();
			for ($i=$start; $i<$end; $i++) {
				$temp = (object)Array();
				if ($way==1) {
					$result = static::getBalanceMonth($userId, $i, $clearCache);
					//print_r($result); 
					$stats[$end-$i] = $result;
				} else {
					$result = static::getBalanceMonth($userId, $end-1-$i, $clearCache);
					//print_r($result); 
					$stats[$i] = $result;
				}
			}
			//exit;
			return $stats;
		});

	}





	//############### получение лидеров рейтинга (для главной) ################
	public static function getLeaders($count, $clearCache = false) {
		if ($clearCache) {
			Cache::forget(static::getCacheKey("leaders:".$count));
		}

		return Cache::remember(static::getCacheKey("leaders:".$count), 60, function() use ($count) {
			$result = Array();

			$query = DB::table('billing')
				->select('user_id', DB::raw('SUM(sum) as summ'))
				->where('type', 'bonus_tariff')
				->orWhere('type', 'cashback')
				->groupBy('user_id')
				->orderBy('summ', 'desc')
				->limit($count)
				->get();

			if ($query) {
				$count=0;
				foreach ($query as $row) {
					$myUser = User::getUser($row->user_id);
					$result[$count]['id'] = $row->user_id;
					$result[$count]['summ'] = $row->summ;
					$result[$count]['name'] = $myUser->name;
					$result[$count]['avatar'] = User::getAvatar($row->user_id, 'small');
					$count++;
				}
			}

			//print_r($result); exit;
			return $result;
		});

	}



	//################## получение суммы всех бонусов юзера за все тарифы #########################
	public static function getTotalBonusesTariffs($userId, $clearCache = false) {
		if ($clearCache) {
			Cache::forget(static::getBalanceCacheKeyAdd($userId, "bonus_tariff"));
		}

		return Cache::remember(static::getBalanceCacheKeyAdd($userId, "bonus_tariff"), 60, function() use ($userId) {
			$result = (object)Array();

			$result->total1 = 0;
			$result->total2 = static::where('user_id', $userId)->where('type', 'bonus_tariff')
				->where('description', 'like', '%GOLD%')->sum('sum') - 0;
			$result->total3 = static::where('user_id', $userId)->where('type', 'bonus_tariff')
				->where('description', 'like', '%BUSINESS START%')->sum('sum') - 0;
			$result->total4 = static::where('user_id', $userId)->where('type', 'bonus_tariff')
				->where('description', 'like', '%BUSINESS OPTIMUM%')->sum('sum') - 0;
			$result->total5 = static::where('user_id', $userId)->where('type', 'bonus_tariff')
				->where('description', 'like', '%BUSINESS MAX%')->sum('sum') - 0;

			$result->total = $result->total1 + $result->total2 + $result->total3 + $result->total4 + $result->total5;
			
			if ($result->total > 0) {
				$result->percent1 = ceil($result->total1 / $result->total * 100);
				$result->percent2 = ceil($result->total2 / $result->total * 100);
				$result->percent3 = ceil($result->total3 / $result->total * 100);
				$result->percent4 = ceil($result->total4 / $result->total * 100);
				$result->percent5 = ceil($result->total5 / $result->total * 100);
			} else {
				$result->percent1 = 0;
				$result->percent2 = 0;
				$result->percent3 = 0;
				$result->percent4 = 0;
				$result->percent5 = 0;
			}

			// хак для того чтобы не было бы 101% при условии всех округлений выше
			$test_max = max($result->percent1, $result->percent2, $result->percent3, $result->percent4, $result->percent5);
            $test_total = $result->percent1 + $result->percent2 + $result->percent3 + $result->percent4 + $result->percent5;
			if ($test_total > 100) {
			    $test_minus = $test_total-100;
                if ($test_max == $result->percent1) {$result->percent1 = $result->percent1 - $test_minus;}
                if ($test_max == $result->percent2) {$result->percent2 = $result->percent2 - $test_minus;}
                if ($test_max == $result->percent3) {$result->percent3 = $result->percent3 - $test_minus;}
                if ($test_max == $result->percent4) {$result->percent4 = $result->percent4 - $test_minus;}
                if ($test_max == $result->percent5) {$result->percent5 = $result->percent5 - $test_minus;}
            }

			//print_r($result); exit;
			return $result;
		});
	}





	// внесение платежа в биллинг
        public static function addPayment($userId, $sum, $description = '') {
            $item = new static();
            $item->fill(['user_id' => $userId, 'sum' => $sum, 'description' => $description, 'created' => Helpers::Now()]);

            if (!$item->save()) {
                throw new BillingException('Unable to add payment', BillingException::GENERAL_ERROR);
            }

            Cache::forget(static::getBalanceCacheKey($userId));
        }




        public static function pay($userId, $sum, $description = '') {
            DB::transaction(function() use ($userId, $sum, $description) {
                $balance = static::getBalance($userId);
                if ($balance < $sum) {
                    throw new BillingException('Not enough money', BillingException::NOT_ENOUGH);
                }

                $item = new static;
                $item->fill(['user_id' => $userId, 'sum' => -$sum, 'description' => $description, 'created' => Helpers::Now()]);

                if (!$item->save()) {
                    throw new BillingException('Unable to add payment', BillingException::GENERAL_ERROR);
                }
            });

            Cache::forget(static::getBalanceCacheKey($userId));
        }



        public static function getPaymentUrl($userId, $sum, $description) {
            return DB::transaction(function() use ($userId, $sum, $description) {
                $invoice = new BillingInvoice(['user_id' => $userId, 'sum' => $sum]);
                try {
                    $invoice->save();
                } catch (\Exception $e) {
                    throw new BillingException('Unable to create invoice', BillingException::UNABLE_TO_CREATE_INVOICE);
                }

                $robokassa = new Robokassa();
                return $robokassa->createRoboPaymentLink($invoice->id, $sum, $description);
            });
        }



        public static function getExtendedHistory($userId) {
            $query = static::where('user_id', $userId)->orderBy('created', 'desc');

            return $query->get();
        }











	//######################### ПРИОБРЕТЕНИЕ ТАРИФА ###################################
	public static function orderTariff($user, $tariff, $notFirstTime = false, $gift_user_id = false) {
		$tariffs = config('tariffs');
		$bonuses = config('bonuses')['bonuses_tariffs'];
		$price = 0;
		$priceToBonuses = 0;

		//################ списание денег из биллинга ################
		if ($notFirstTime != true) {

			// подсчет стоимость тарифа при уcловии вычета цены предыдущего тарифа
			$price = $tariff['price'] - $tariffs[ $user->tariff ]['price'];
			$priceToBonuses = $tariff['price'];

			// при подарке тарифа от одного юзера другому
			if ($gift_user_id != false) {
				static::pay($user->id, 0, 'Подаренная карта и тариф "' . $tariff['name'] . '" от пользователя ID '.$gift_user_id);
				$priceToBonuses = 0;
			}

			// пре апгрейде
			else if ($tariffs[ $user->tariff ]['price'] != 0) {
				static::pay($user->id, $price, 'Переход с предыдущего тарифа "' . $tariffs[ $user->tariff ]['name'] . '" на новый тариф "' . $tariff['name'] . '"');

			// при приобретении после нулевого тарифа
			} else {
				static::pay($user->id, $price, 'Приобретение тарифа "' . $tariff['name'] . '"');
			}
		}

		//внесение тарифа в историю тарифов
		if ($gift_user_id == false || $gift_user_id == 0) {$gift_user_id = NULL;}
		BillingTariffs::insert([
			'user_id' => $user->id, 
			'start' => Helpers::Now(), 
			'sum' => $price, 
			'tariff' => $tariff['name'],
			'gift_user_id' => $gift_user_id,
		]);

		// если тариф просто обновляется и карта уже была заведена
		$result = BillingCards::where('user_id', $user->id)->orderBy('id', 'desc')->first();
		if ($result) {
			$num_card = $result->num_card;
			$num_qrcode = $result->num_qrcode;
			$num_barcode = $result->num_barcode;
			$num_count = $result->num_count;
			$num_type = $result->num_type;
			$num_brand = $result->num_brand;

		// если карта генируруется первый раз
		} else {
			$result = BillingCards::where('num_type', 1)->where('num_brand', 1)->orderBy('num_count', 'desc')->first();
			$num_card = "0000010001" . str_pad(($result->num_count + 1), 6, '0', STR_PAD_LEFT); // 1-эл.карта
			$num_qrcode = "210001" . str_pad(($result->num_count + 1), 6, '0', STR_PAD_LEFT); // 1-эл.карта
			$num_barcode = Helpers::barcodeNumber($num_qrcode); // добавление контрольной суммы
			$num_count = $result->num_count + 1;
			$num_type = 1; // 1 - электронная карта
			$num_brand = 1; // 0001
		}  

		//внесение карты в историю карт
		BillingCards::insert([
			'user_id' => $user->id, 
			'start' => Helpers::Now(), 
			'num_type' => $num_type,
			'num_brand' => $num_brand,
			'num_count' => $num_count,
			'num_card' => $num_card,
			'num_barcode' => $num_barcode,
			'num_qrcode' => $num_qrcode,
			'tariff' => $tariff['name'],
		]);

		//обновление тарифа у текущего юзера
		User::where('id', $user->id)->update(['tariff' => $tariff['name']]);

		if ($notFirstTime == false && $priceToBonuses > 0) {

			//  Внесение процентов бонусов для ВСЕХ уровней выше
			static::payBonusesTariffsToReferrals($user->id, $priceToBonuses, false);

			//добавление баллов в рейтинг, для всех вышестоящих афиллатов
			Rating::payRatingToReferrals($user->id, false);

			//  Внесение бонусов BonuxBox для ВСЕХ уровней выше
			static::payBonusesBoxToReferrals($user->id, false);

		}



	}








    /**
     ###############################################################################
     * Начисляет бонусы за покупку тарифа всем уровням рефералов выше купившего юзера
     * @param $userId integer идентификатор пользователя, купившего пакет
     * @param $sum integer сумма покупки
     * @return null
     */
	public static function payBonusesTariffsToReferrals($userId, $sum, $testOnly = false) {
        $bonusMatrix = config('bonuses.bonuses_tariffs');

        $level = 1;
        $user = User::find($userId);
	    $descriptionTariff = $user->tariff;
	    $descriptionUserId = $user->id;

        while ($level <= 8 && $user) {
            if (empty($user->ref_id)) break;
            $user = User::find($user->ref_id);
            if (!$user) break;

            $percent = $bonusMatrix[$user->tariff][$level] ?? 0;
            if ($percent) {

                // Выбрасывваем из цикла если куплен GOLD и уровень больше 1
                if ($descriptionTariff=='GOLD' && $level>1) {break;}

                // Считаем сумму бонуса
                if ($descriptionTariff=='GOLD' && $level==1) {
                    $bonusSum = 100; // хак для голда - только 100 рублей
                } else {
                        $bonusSum = round($sum * $percent / 100);
                }

                $bonusDescription = 'На твоем тарифе "' . $user->tariff . '" начислен бонус за покупку тарифа "' . $descriptionTariff . '" пользователем ID ' . $descriptionUserId .' на партнёрском уровне ' . $level;
                if ($testOnly) {print "$bonusSum RUR - $bonusDescription\n";}

                    if ($bonusSum > 0) {
                    $billing = new Billing();
                    $billing->fill([
                      'user_id' => $user->id,
                      'created' => date('Y-m-d H:i:s'),
                      'sum' => $bonusSum,
                      'description' => $bonusDescription,
                      'data' => json_encode(['user_id' => $userId]),
                      'type' => 'bonus_tariff',
                    ]);
                    if (!$testOnly) {
                      $billing->save();
                    }
                }
            }
            if ($testOnly) {print "To user $user->id - from user $descriptionUserId tariff $descriptionTariff - in level $level - bonus $percent %\n\n";}
            ++$level;
        }
        if ($testOnly) {exit;}
    }






    /**
     * ###############################################################################
     * Начисляет бонус-бокс за покупку любого бизнес-тарифа всем уровням рефералов выше купившего юзера
     * @param $userId integer идентификатор пользователя, купившего пакет
     * @return null
     */
	public static function payBonusesBoxToReferrals($userId, $testOnly = false)
    {
        $bonusMatrix = config('bonuses.bonuses_box');

        $level = 1;
        $user = User::find($userId);

        // перебираем все уровни всех реферралов от данного купившего и "вверх"
        while ($level <= 8 && $user)
        {
            if (empty($user->ref_id)) break;
            $user = User::find($user->ref_id);
            if (!$user) break;
            if ($testOnly) {print "\n\n\n\n\n\n\n\n\n\n=====================================================\nЮзер $user->id уровень $level тариф $user->tariff\n";}
            if (empty($bonusMatrix[$user->tariff][$level])) {++$level; continue;}

            $percent = $bonusMatrix[$user->tariff][$level]['percent'] ?? 0;
            $packs = $bonusMatrix[$user->tariff][$level]['packs'] ?? 0;

            if ($percent && $packs)
            {

                // получаем массив всех реферралов от проверяемого нами юзера
                $usersArray = User::getReferalArrayLevels($user->id);
                if ($testOnly) {print "==============\nМассив всех реферралов юзера $user->id на всех уровнях\n"; print_r($usersArray);}

                // и берем от него только нужный нам уровень (на котором находится юзер купивший тариф)
                if (empty($usersArray[$level-1])) {++$level; continue;}
                $usersArrayToWhere = array_keys($usersArray[$level-1]);
//                $usersArrayToWhere = implode(",",$usersArrayToWhere);
                if ($testOnly) {print "===========\n\nМассив всех реферралов юзера $user->id на уровне $level\n"; print_r($usersArrayToWhere);}
//                if ($testOnly) {print "===========\n\nМассив всех реферралов юзера $user->id на уровне $level РЕФЕРРАЛЫ: $usersArrayToWhere\n";}

                // Подсчитываем количество всех ранее купленных бизнес-пакетов у всех афиллатов по полученному массиву
                $purchasedTariffs = BillingTariffs::
                    where('sum', '>', 0)->
                    whereNull('gift_user_id')->
                    where('tariff', 'like', '%BUSINESS%')->
                    whereIn('user_id', $usersArrayToWhere)->
                    count();

                if ($testOnly) {print "----------\nРезультат из БД на уровне $level количество купленных бизнес-пакетов purchasedTariffs = $purchasedTariffs\n";}

                // если наступило то самое "число х" когда количество купленных пакетов совпало
                if($purchasedTariffs > 0 && $purchasedTariffs/$packs == ceil($purchasedTariffs/$packs))
                {

                    $queryTariffs = BillingTariffs::
                        where('sum', '>', 0)->
                        whereNull('gift_user_id')->
                        where('tariff', 'like', '%BUSINESS%')->
                        whereIn('user_id', $usersArrayToWhere)->
                        orderBy('start', 'desc')->
                        limit($packs)->
                        get();

                    if ($testOnly) {
                        print "----------\nВывод всех найденных $packs тарифов и их сумм\n";
                        print "кол-во найдено " . count($queryTariffs) . "\n";
                        foreach ($queryTariffs as $row) {
                            print "id $row->id user_id $row->user_id sum $row->sum tariff $row->tariff\n";
                        }
                    }

                    // Считаем сумму бонуса
                    $sum = 0;
                    foreach ($queryTariffs as $row) {
                        $sum += $row->sum;
                    }

                    if ($testOnly) {print "----------\nСумма бонуса sum = $sum\n";}
                    $bonusSum = round(($sum / $packs) * $percent / 100);

                    //$bonusDescription = 'На твоем тарифе "' . $user->tariff . '" на партнёрском уровне '.$level.' начислен разовый BonusBox за купленные очередные '.$packs.' тарифов в размере '.$percent.'% от общей суммы '.$sum;
                    $bonusDescription = 'На твоем тарифе "' . $user->tariff . '" на партнёрском уровне '.$level.' начислен разовый BonusBox за купленные очередные '.$packs.' тарифов';
                    if ($testOnly) {print "----------\nВнесено в биллинг! $bonusSum RUR - $bonusDescription\n";}

                    if ($bonusSum > 0)
                    {
                        $billing = new Billing();
                        $billing->fill([
                            'user_id' => $user->id,
                            'created' => date('Y-m-d H:i:s'),
                            'sum' => $bonusSum,
                            'description' => $bonusDescription,
                            'data' => json_encode(['user_id' => $userId]),
                            'type' => 'bonus_box',
                        ]);
                        if (!$testOnly) {$billing->save();}
                    }

                }

            }
            if ($testOnly) {print "----------\nTo user  $user->id ref_id $user->ref_id - in level $level - packs $packs - percent $percent %\n\n";}
            ++$level;
        }
        if ($testOnly) {exit;}
    }





}