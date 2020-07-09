<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\Partnerer;
use App\Models\PartnererCategory;
use App\Models\PartnererTerritory;
use App\Models\PartnererPartners;
use App\Exceptions\PartnererException;

//use App\Models\Test;
//use App\Models\TestCategory;
//use App\Models\TestSession;
//use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Cookie\CookieJar;

class Controller extends BaseController {
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



	//############## ПРОСТЫЕ ТЕКСТОВЫЕ СТРАНИЦЫ ##############
	public function getIndexAbout(Request $request) {
		return view('about', ['user' =>  Auth::user()]);
		}
	public function getIndexDocs(Request $request) {
		return view('docs', ['user' =>  Auth::user()]);
		}
	public function getIndexSearch(Request $request) {
		return view('search', ['user' =>  Auth::user()]);
		}
	public function getIndexCooperation(Request $request) {
		return view('cooperation', ['user' =>  Auth::user()]);
		}
	public function getIndexFaq(Request $request) {
		return view('faq', ['user' =>  Auth::user()]);
		}
	public function getIndexJob(Request $request) {
		return view('job', ['user' =>  Auth::user()]);
		}
	public function getIndexContacts(Request $request) {
		return view('contacts', ['user' =>  Auth::user()]);
		}
	public function getIndexOferta(Request $request) {
		return view('oferta', ['user' =>  Auth::user()]);
		}
	public function getIndexUsage(Request $request) {
		return view('usage', ['user' =>  Auth::user()]);
		}



	//############## НОВОСТИ ##############
	public function getNewsList(Request $request) {
		return view('news.list', ['user' =>  Auth::user()]);
		}
	public function getNews(Request $request) {
		$blade = "news." . $request->y . "." . $request->m . "." . $request->d . "." . $request->file;
		return view($blade, ['user' =>  Auth::user()]);
		}



	//############## ГЛАВНАЯ СТРАНИЦА САЙТА ##############
	public function getIndexPage(CookieJar $cookieJar, Request $request)  {

		$user = Auth::user();

		// проверка на вход по реферральной ссылке (только если юзер не авторизован)
		if (preg_match('#^ref([0-9a-z]+)#', $_SERVER['QUERY_STRING'], $matches)) {
			if (empty($user->id)) {
				$referal = User::where('referal_code', $matches[1])->first();
				if ($referal) {
					$cookieJar->queue(cookie('referred', $referal->id, 45000));
				}
			}
		return redirect()->to('/');
		}

		$data = [
			'user' => $user,
			'indexPage' => 1,
			];
		return view('index', $data);
	}





}
