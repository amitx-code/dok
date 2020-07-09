<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;

class PartnererCategory extends CachedModel {
        protected $table = 'partnerer_categories';
        public $timestamps = false;
        protected $guarded = array('id');



        public function SetDefaultFields($user) {
            parent::SetDefaultFields($user);
            $this->sort = time();
        }





	//############## ПОЛУЧЕНИЕ ПАРТНЕРОВ ОТОБРАННЫХ ПО КАТЕГОРИЯМ И ПРАВАМ ##############
	public function getFindPartnersAttribute() {

		if ($this->id) { // если выбрана категория
			$where1 = Array(['category_id', '=', $this->id]);
		} else { // если показать все категории
			$where1 = Array();
		}

		return PartnererPartners::where($where1)->orderBy('id', 'desc')->get();
	}




	//############## ПОЛУЧЕНИЕ ПАРТНЕРОВ ДЛЯ ИНДЕКСНОЙ СТРАНИЦЫ ##############
	public function getFindPartnersIndexpage() {
		$where = Array(['show_indexpage', '=', 'Y']);
		return PartnererPartners::where($where)->orderBy('id', 'desc')->get();
	}



	//############## ПОЛУЧЕНИЕ ПАРТНЕРОВ ДЛЯ СТРАНИЦЫ КАТЕГОРИЙ ##############
	public function getFindPartnersCategorypage() {
		$where = Array(['show_categorypage', '=', 'Y']);
		return PartnererPartners::where($where)->orderBy('id', 'desc')->get();
	}



}