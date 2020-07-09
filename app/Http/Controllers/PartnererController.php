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
use Illuminate\Support\Facades\Route;


class PartnererController extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;




	//############## ПРОСТЫЕ ТЕКСТОВЫЕ СТРАНИЦЫ ##############
	public function getSearch(Request $request) {
		return view('admin.partnerer.search');
		}
	public function getHelp(Request $request) {
		return view('admin.partnerer.help');
		}



	//############## СПИСОК ВСЕХ ПАРТНЕРОВ ##############
	public function getList(Request $request) {

		$user = Auth::user();

		// территории документов для верхних вкладок
		$territories = PartnererTerritory::orderBy('name')->get();
		$territory = PartnererTerritory::find($request->territory_id);
		$territoryId = $territory->id ?? 0;

		// категории документов для верхних вкладок
		$categories = PartnererCategory::orderBy('sort')->get();
		$category = PartnererCategory::find($request->category_id);
		$categoryId = $category->id ?? 0;

		// проверка территории на совпадение с городом указанным в личных данных и редирект если город не задан
		if ($territoryId==0 && $user->city!="") {
			foreach ($territories as $territory) {
				if ($territory->name == $user->city ) {
					$redirect = "/admin/partnerer/list/". $categoryId . "-" . $territory->id;
					if (!empty($_SERVER['QUERY_STRING'])) {$redirect .= "?" . $_SERVER['QUERY_STRING'];}
					return redirect($redirect);
				}
			}
		}

		// получение документов с учетом выбранной категории
		$partners = $category->getFindPartnersAttribute();
	
		// отсев из них документов не подходящих для выбранной территории
		foreach ($partners as $key=>&$arrayElement) {
			if (
				(
					$territoryId > 1 && 
					$partners[$key]->territory_id ==""
				) || (
					$territoryId > 1 &&
					$partners[$key]->territory_id !="" && 
					!preg_match("/\,\s*$territoryId\s*\,/", ",".$partners[$key]->territory_id.",") && 
					!preg_match("/\,\s*2\s*\,/", ",".$partners[$key]->territory_id.",") && // хак для "всей России"
					!preg_match("/^\s*2\s*\,/", ",".$partners[$key]->territory_id.",") // хак для "всей России"
				)
			) {
				 unset( $partners[$key] );
			}
		}

		// параметры поиска, если они есть
		$search = Array();
		$temp = Array();
		$search['url1'] = "";
		$search['url2'] = "";
		if ($request->text) {$search['text'] = $request->text;			$temp[] .= "text=" . $request->text;}
		if ($request->now) {$search['now'] = $request->now;		$temp[] .= "now=" . $request->now;}
		if ($request->near) {$search['near'] = $request->near;		$temp[] .= "near=" . $request->near;}
		if ($request->online) {$search['online'] = $request->online;	$temp[] .= "online=" . $request->online;}
		if (count($temp) > 0) {
			$search['url1'] = "&" . implode("&", $temp);
			$search['url2'] = "?" . implode("&", $temp);
		}

		// отсев из них документов не подходящих для поиска
		if ($search) {
			foreach ($partners as $key=>&$arrayElement) {
				if (
					(
						$request->text &&
						!preg_match("/$request->text/umi", $partners[$key]->name) &&
						!preg_match("/$request->text/umi", $partners[$key]->title) &&
						!preg_match("/$request->text/umi", $partners[$key]->description)
					) || (
						1==2
					)
				) {
					 unset( $partners[$key] );
				}
			}
		}

		// отбор только нужного количества для нужной страницы
		$pages = Array();
		$perPage = 15;
		$total = count($partners);
		$pages['now'] = $request->page; if (empty($pages['now'])) {$pages['now']=1;}
		$pages['total'] = ceil($total / $perPage);

		$pages['min'] = 1;
		if ($pages['now'] > 4) {$pages['min'] = $pages['now'] - 4;}

		$pages['max'] = $pages['now'] + 5;
		if ($pages['max'] < 10) {$pages['max'] = 10;}
		if ($pages['max'] > $pages['total']) {$pages['max'] = $pages['total'];}

		if (($pages['total'] - $pages['max']) < 10 && $pages['now'] > 10) {$pages['min'] = $pages['max']  - 9;}

		if ($pages['total'] > 10 && $pages['now'] > 5) {$pages['start'] = 1;}
		if ($pages['total'] > 10 && $pages['now'] < $pages['total'] - 5) {$pages['end'] = $pages['total'];}

		$itemStart = ($pages['now'] - 1) * $perPage;
 		$itemEnd = $itemStart + $perPage - 1;
		if ($total > $perPage) {
			$i = 0;
			foreach ($partners as $key=>&$arrayElement) {
				if ($i > $itemEnd || $i < $itemStart) {
					unset( $partners[$key] );
				}
				$i++;
			}
		}

		// при работе из мобильных приложений
		if (Route::currentRouteName() == "mobile") {

			return response()->json([
				'time' => time(),
				'error' => 0,
				'errorMessage' => '',
				'data' => [
					'online' => [],
					'items' => [
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
						'0' => [
						    'date' => 1562511821,
						    'user_id' => 580689,
						    'api_token' => 0, // TODO: временно добавил пока в моб.прил проверяется это в списке партнеров
						    'avatar' => '/files/photos11/580689-11135465145d2207b9c52a8.jpg',
						    'myname' => 'Test Test',
						    'age' => 22,
						    'sex' => 'f',
						    'height' => 167,
						    'weight' => 49,
						    'email' => 'text@text.text',
						    'lang' => 'ru',
						    'ru_name' => 'text  ',
						    'text' => 'text text text text text  ',
						    'ru_text' => 'text text text text  ',
						    'country' => 3159,
						    'region' => 4312,
						    'city' => 4400,
						    'country2' => 3159,
						    'region2' => 4312,
						    'city2' => 4400,
						    'what' => 11,
						    'country_ru' => 'Россия',
						    'region_ru' => 'Москва и Московская обл.',
						    'city_ru' => 'Москва',
						    'country2_ru' => 'Россия',
						    'region2_ru' => 'Москва и Московская обл.',
						    'city2_ru' => 'Москва',
						    'bann' => '',
						    'moderated' => '',
						    'delete' => '',
						    '_id' => 580692,
						    '_type' => 652,
						    '_sort' => 580692,
						    'name' => 'Название партнера 1',
						    '_name' => 'Название партнера 1',
						    '_path' => 'main.ru.love.580692',
						    '_code' => 580692,
						    '_codepage' => 1,
						    '_url' => '/ru/love/580692.html',
						    '_file' => '/ru/love/ad692/580692.html',
						    '_parent_id' => 641,
						    'user' => [
								'id' => 580689,
								'doc' => [
									  '__xml' => [],
									  'error' => '',
								],
								'login' => 'Klever',
								'email' => 'kukushkas@protonmail.com',
								'_name' => 'Klever',
								'_url' => '/ru/users/document580689.phtml',
								'create_time' => 1562510852,
								'lang' => 'ru',
								'delete' => '',
								'username' => 'Ромашка',
								'country' => 3159,
								'country_ru' => 'Россия',
								'region' => 4312,
								'region_ru' => 'Москва и Московская обл.',
								'city' => 4400,
								'city_ru' => 'Москва',
								'address' => '',
								'phone' => '',
								'company' => '',
								'position' => '',
								'sex' => 'f',
								'avatar' => 'http://loverium.ru/files/photos11/580689-3713049855d223dec008b9.jpg',
								'real' => '',
								'birthday' => 856040400,
								'age' => 22,
								'birthday1' => 16,
								'birthday2' => 2,
								'birthday3' => 1997,
								'height' => 167,
								'weight' => 49,
								'is_moderator' => '',
								'code' => '',
								'unique' => '6eb01872460264b41b9e75473235248c',
								'bann_unique' => '',
								'moderator' => '',
								'rating' => 0,
								'relogin' => '',
								'text' => 'За здоровый образ жизни без вредных привычек ',
								'displayname' => 'Ромашка',
								'discount_cumulative' => '',
								'discount_percent' => '',
								'discount_rur' => '',
								'discount_usd' => '',
								'discount_eur' => '',
								'confirmed' => '',
								'photos' => [],
								'photosCount' => 1,
								'online' => 1,
							  ],
						],
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
/*
						'1' => [
						    'date' => 1562511821,
						    'user_id' => 580689,
						    'avatar' => 'http://loverium.ru/files/photos11/580689-11135465145d2207b9c52a8.jpg',
						    'myname' => 'Роза',
						    'age' => 22,
						    'sex' => 'f',
						    'height' => 167,
						    'weight' => 49,
						    'email' => 'kukushkas@protonmail.com',
						    'lang' => 'ru',
						    'ru_name' => 'Ищу мужчину ',
						    'text' => 'Ищу мужчину с поддержкой для серьезных отношений ',
						    'ru_text' => 'Ищу мужчину с поддержкой для серьезных отношений ',
						    'country' => 3159,
						    'region' => 4312,
						    'city' => 4400,
						    'country2' => 3159,
						    'region2' => 4312,
						    'city2' => 4400,
						    'what' => 11,
						    'country_ru' => 'Россия',
						    'region_ru' => 'Москва и Московская обл.',
						    'city_ru' => 'Москва',
						    'country2_ru' => 'Россия',
						    'region2_ru' => 'Москва и Московская обл.',
						    'city2_ru' => 'Москва',
						    'bann' => '',
						    'moderated' => '',
						    'delete' => '',
						    '_id' => 580692,
						    '_type' => 652,
						    '_sort' => 580692,
						    '_name' => 'Ищу мужчину ',
						    '_path' => 'main.ru.love.580692',
						    '_code' => 580692,
						    '_codepage' => 1,
						    '_url' => '/ru/love/580692.html',
						    '_file' => '/ru/love/ad692/580692.html',
						    '_parent_id' => 641,
						    'user' => [
								'id' => 580689,
								'doc' => [
									  '__xml' => [],
									  'error' => '',
								],
								'login' => 'Klever',
								'email' => 'kukushkas@protonmail.com',
								'_name' => 'Klever',
								'_url' => '/ru/users/document580689.phtml',
								'create_time' => 1562510852,
								'lang' => 'ru',
								'delete' => '',
								'username' => 'Ромашка',
								'country' => 3159,
								'country_ru' => 'Россия',
								'region' => 4312,
								'region_ru' => 'Москва и Московская обл.',
								'city' => 4400,
								'city_ru' => 'Москва',
								'address' => '',
								'phone' => '',
								'company' => '',
								'position' => '',
								'sex' => 'f',
								'avatar' => 'http://loverium.ru/files/photos11/580689-3713049855d223dec008b9.jpg',
								'real' => '',
								'birthday' => 856040400,
								'age' => 22,
								'birthday1' => 16,
								'birthday2' => 2,
								'birthday3' => 1997,
								'height' => 167,
								'weight' => 49,
								'is_moderator' => '',
								'code' => '',
								'unique' => '6eb01872460264b41b9e75473235248c',
								'bann_unique' => '',
								'moderator' => '',
								'rating' => 0,
								'relogin' => '',
								'text' => 'За здоровый образ жизни без вредных привычек ',
								'displayname' => 'Ромашка',
								'discount_cumulative' => '',
								'discount_percent' => '',
								'discount_rur' => '',
								'discount_usd' => '',
								'discount_eur' => '',
								'confirmed' => '',
								'photos' => [],
								'photosCount' => 1,
								'online' => 1,
							  ],
						],
*/
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
					], 
				],
			]);



		// при работе из веб-сайта
		} else {
			$data = [
                'search' => $search,
				'territories' => $territories,
				'territoryId' => $territoryId,
				'categories' => $categories,
				'categoryId' => $categoryId,
				'partners' => $partners,
				'pages' => $pages,
				];
			return view('admin.partnerer.list', $data);
		}

	}







/*
	//############## ПОИСК РЕЗУЛЬТАТЫ (работает точно так же как СПИСОК ВСЕХ ПАРТНЕРОВ) ##############
	public function postSearch(Request $request) {


		$user = Auth::user();

		// территории документов для верхних вкладок
		$territories = PartnererTerritory::orderBy('name')->get();
		$territory = PartnererTerritory::find($request->territory_id);
		$territoryId = $territory->id ?? 0;

		// категории документов для верхних вкладок
		$categories = PartnererCategory::orderBy('sort')->get();
		$category = PartnererCategory::find($request->category_id);
		$categoryId = $category->id ?? 0;

		// проверка территории на совпадение с городом указанным в личных данных и редирект если город не задан
		if ($territoryId==0 && $user->city!="") {
			foreach ($territories as $territory) {
				if ($territory->name == $user->city ) {
					return redirect("/admin/partnerer/list/". $categoryId . "-" . $territory->id );
				}
			}
		}

		// получение документов с учетом выбранной категории
		$partners = $category->getFindPartnersAttribute();

		// отсев из них документов не подходящих для выбранной территории
		foreach ($partners as $key=>&$arrayElement) {
			if (
				(
					$territoryId > 1 && 
					$partners[$key]->territory_id ==""
				) || (
					$territoryId > 1 &&
					$partners[$key]->territory_id !="" && 
					!preg_match("/\,\s*$territoryId\s*\,/", ",".$partners[$key]->territory_id.",")
				)
			) {
				 unset( $partners[$key] );
			}
		}

		// отбор партнеров только нужного количества для нужной страницы
		$pages = Array();
		$perPage = 15;
		$total = count($partners);
		$pages['now'] = $request->page; if (empty($pages['now'])) {$pages['now']=1;}
		$pages['total'] = ceil($total / $perPage);

		$pages['min'] = 1;
		if ($pages['now'] > 4) {$pages['min'] = $pages['now'] - 4;}

		$pages['max'] = $pages['now'] + 5;
		if ($pages['max'] < 10) {$pages['max'] = 10;}
		if ($pages['max'] > $pages['total']) {$pages['max'] = $pages['total'];}

		if (($pages['total'] - $pages['max']) < 10 && $pages['now'] > 10) {$pages['min'] = $pages['max']  - 9;}

		if ($pages['total'] > 10 && $pages['now'] > 5) {$pages['start'] = 1;}
		if ($pages['total'] > 10 && $pages['now'] < $pages['total'] - 5) {$pages['end'] = $pages['total'];}

		$itemStart = ($pages['now'] - 1) * $perPage;
 		$itemEnd = $itemStart + $perPage - 1;
		if ($total > $perPage) {
			$i = 0;
			foreach ($partners as $key=>&$arrayElement) {
				if ($i > $itemEnd || $i < $itemStart) {
					unset( $partners[$key] );
				}
				$i++;
			}
		}

		// финальная передача данных
		$data = [
			'searchString' => $request->searchString,
			'territories' => $territories,
			'territoryId' => $territoryId,
			'categories' => $categories,
			'categoryId' => $categoryId,
			'partners' => $partners,
			'pages' => $pages,
			];
		return view('admin.partnerer.list', $data);
		}
*/







	//############## ПОКАЗ КАРТОЧКИ ПАРТНЁРА  ##############
	public function getCard(Request $request) {

		$user = Auth::user();

		// простейшие проверки
		$partner = Partnerer::find($request->id);
		if (!$partner) {
			return redirect('/admin/partnerer/list');
		}

		$categories = PartnererCategory::orderBy('sort')->get();

		// имя категории партнёра
		foreach ($categories as $category) {
			if($category->id == $partner->category_id) {
				$partner->category_name = $category->name;
			}
		}

		// Подготовка партнерской ссылки с передачей subid
		if ($partner->url_partnerlink) {
			if (preg_match("/\?/", $partner->url_partnerlink)) {
				$partner->url_partnerlink .= "&subid=" . $user->id;
			} else {
				$partner->url_partnerlink .= "?subid=" . $user->id;
			}
		}

		// территория текущая передаваемая в урле
		$territoryId = $request->territory_id ?? 0;

		// финальная передача данных
		$data = [
			'territoryId' => $territoryId,
			'partner' => $partner,
		];
		return view('admin.partnerer.list_card', $data);
	}





}
