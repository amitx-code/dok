<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;

class PurchaserCategory extends CachedModel {
        protected $table = 'purchaser_categories';
        public $timestamps = false;
        protected $guarded = array('id');



        public function SetDefaultFields($user) {
            parent::SetDefaultFields($user);
            $this->sort = time();
        }



	//############## ПОЛУЧЕНИЕ ДОКУМЕНТОВ ОТОБРАННЫХ ПО КАТЕГОРИЯМ ##############
	public function getFindDocsAttribute() {

		if ($this->id) { // если выбрана категория
			$where1 = Array(['category_id', '=', $this->id]);
		} else { // если показать все категории
			$where1 = Array();
		}

		$user = Auth::user();
		$where2 = Array(['user_id', '=', $user->id]);

		return Purchaser::where($where1)->where($where2)->orderBy('id', 'desc')->get();
	}


}