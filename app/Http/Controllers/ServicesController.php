<?php

namespace App\Http\Controllers;

use App\Exceptions\BillingException;
use App\Models\Billing;
use App\Models\User;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ServicesController extends BaseController
{



	//############## СПИСОК СЕРВИСОВ ##############
	public function getList(Request $request) {
		return view('admin.services.list');
	}




	//############## ПРИОБРЕТЕНИЕ СЕРВИСОВ ##############
	public function getService(Request $request) {
		$user = Auth::user();
		
		if (empty($request->service_id) || !is_numeric($request->service_id)) {
			return redirect('/admin/services/list');
		}
		if ($request->service_id > 5 || $request->service_id < 1) {
			return redirect('/admin/services/list');
		}

		$data = [
			'request' => $request,
			'user' =>$user,
			'balance' => Billing::getBalance($user->id),
		];

		return view('admin.services.service' . $request->service_id, $data);
	}




	//############## ФИНАЛЬНАЯ ПОКУПКА УСЛУГИ ##############
	public function postOrder(Request $request) {

		$user = Auth::user();

		//приобретение тарифа
		//Billing::orderTariff($user, $tariffs[$request->tariff]);

		return redirect('/admin');
	}

}
