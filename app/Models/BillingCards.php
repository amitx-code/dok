<?php

namespace App\Models;

use App\Libs\Helpers;

class BillingCards extends CachedModel {
	protected $table = 'billing_cards';
	public $timestamps = false;
	protected $guarded = array('id');





	//############## получение у юзера номера карты, номера баркода или qr-кода ##############
	public static function getCard($user_id, $type) {
		$card = static::where('user_id', $user_id)->orderBy('start', 'desc')->first();
		if ($card) {
			if ($type == "ccard") {
				return $card->num_card;
			} else if ($type == "barcode") {
				return $card->num_barcode;
			} else if ($type == "qrcode") {
				return $card->num_qrcode;
			}
		}
		return false;
	}




}