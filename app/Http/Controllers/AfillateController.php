<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\Partnerer;
use App\Models\PartnererCategory;
use App\Models\PartnererTerritory;
use App\Models\PartnererPartners;
use App\Exceptions\PartnererException;

use App\Libs\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Config;


class AfillateController extends BaseController {




	//############## ПОЛЬЗОВАТЕЛИ ВЫБРАННОГО УРОВНЯ ##############
	public function getUsers($level, $tarif = '') {

		$tariffs = config('tariffs');

		$data = [
			'subUsers' => Auth::user()->getSubUsersTarifLevel($level, $tarif),
			'level' => $level,
			'tarif' => $tarif,
			'tariffs' => $tariffs,
		];

	return view('admin.afillater.users', $data);
	}





}
