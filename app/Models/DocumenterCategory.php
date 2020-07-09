<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;

class DocumenterCategory extends CachedModel {
        protected $table = 'documenter_categories';
        public $timestamps = false;
        protected $guarded = array('id');



        public function SetDefaultFields($user) {
            parent::SetDefaultFields($user);
            $this->sort = time();
        }






	//############## ПОЛУЧЕНИЕ ДОКУМЕНТОВ ОТОБРАННЫХ ПО КАТЕГОРИЯМ И ПРАВАМ ##############
	public function getFindDocsAttribute() {

		if ($this->id) { // если выбрана категория
			$where1 = Array(['category_id', '=', $this->id]);
		} else { // если показать все категории
			$where1 = Array();
		}

		$user = Auth::user();
		if ($user->role_id < 500) { // Сотрудники у которых доступ только к своим документам и своим клиентам
			$where2 = Array(['user_id', '=', $user->id]);
		}
		else { // руководство, полный доступ
			$where2 = Array();
			}

		return Documenter::where($where1)->where($where2)->orderBy('id', 'desc')->get();
	}





	//############## ПОЛУЧЕНИЕ ДОКУМЕНТОВ ИЗ ШАБЛОНОВ, ОТОБРАННЫХ ПО КАТЕГОРИЯМ И ПРАВАМ #####
	public function getFindDocsFromTemplatesAttribute() {

		if ($this->id) { // если выбрана категория
			$where1 = Array(['category_id', '=', $this->id]);
		} else { // если показать все категории
			$where1 = Array();
		}

		$user = Auth::user();
		if ($user->role_id < 500) { // Сотрудники у которых доступ только к своим документам и своим клиентам
			$where2 = Array(['user_id', '=', $user->id]);
		}
		else { // руководство, полный доступ
			$where2 = Array();
			}

		return Documenter::where('template_id',  '>', 0)->where($where1)->where($where2)->orderBy('id', 'desc')->get();
	}




}