<?php

namespace App\Models;

use Illuminate\Support\Facades\Config;

class BillingTariffs extends CachedModel {
	protected $table = 'billing_tariffs';
	public $timestamps = false;
	protected $guarded = array('id');






	//############## получение количества оставшихся подарочных карт у юзера ##############
	public static function getCardsGiftLeft($user, $tariff) {
		$tariffs = config('tariffs');

		if ($tariff == 'GOLD') {$gift=1;}
		else if ($tariff == 'BUSINESS START') {$gift=2;}
		else {return 0;}

		$total = static::where('gift_user_id', $user->id)->where('tariff', $tariff)->count();
		if ($total < $tariffs[$user->tariff]['cards_gifts'.$gift]) {
			return $tariffs[$user->tariff]['cards_gifts'.$gift] - $total;
		}
		
		return 0;
	}



}