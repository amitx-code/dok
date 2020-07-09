<?php

namespace App\Http\Controllers;

//use App\Models\Billing;
use App\Models\Rating;
use App\Libs\Helpers;

use Illuminate\Support\Facades\Auth;
//use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
//use Illuminate\Foundation\Validation\ValidatesRequests;
//use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Config;

class RatingController extends BaseController {

//	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;





	//############## ИСТОРИЯ РЕЙТИНГА ##############
	public function getHistory(Request $request) {

		$user = Auth::user();
		$ratings = config('ratings');
		$history = Rating::getExtendedHistory($user->id);
		$rating = Rating::getUserRating($user->id, true); // TODO: убрать потом очистку кэша!

		$data = [
			'request' => $request,
			'user' =>$user,
			'history' => $history,
			'ratings' =>$ratings,
			'rating' =>$rating,
		];
		return view('admin.rating.history', $data);
	}






}
