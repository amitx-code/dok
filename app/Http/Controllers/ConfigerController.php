<?php

namespace App\Http\Controllers;

use App\Models\ConfigerUsers;
use App\Models\ConfigerUsersRoles;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Config;

use \ConvertApi\ConvertApi;

class ConfigerController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


	//############## ПРОСТЫЕ ТЕКСТОВЫЕ СТРАНИЦЫ ##############
	public function getHelp(Request $request) {
		return view('admin.configer.help');
		}
	public function getConvertapi(Request $request) {
		ConvertApi::setApiSecret( Config('convertapi.ApiSecret') );
		$info = ConvertApi::getUser();
		$data = [
			'SecondsLeft' => $info['SecondsLeft'],
			];
		return view('admin.configer.convertapi', $data);
		}





	//############## ШАГ 1 ЗАПРОСА НОВОГО ТОКЕНА ДЛЯ API ЧУЖОГО САЙТА ##############
	public function getToken(Request $request) {
		$data = [
			'step' => 1,
			];
		return view('admin.configer.api_token', $data);
		}

	//############## ШАГ 2 ЗАПРОСА НОВОГО ТОКЕНА ДЛЯ API ЧУЖОГО САЙТА ##############
	public function postToken(Request $request) {

		// простейшие проверки
		if (!($request->name) || !($request->url)) {
			return redirect('/admin/configer/token');
		}

		// внесение сайта в базу данных
		$oauth_client = new \App\Models\oAuthClient();
		$oauth_client->name = $request->name;
		$oauth_client->secret = hash_hmac('md5', time(), "secret", false);
		$oauth_client->redirect = $request->url; // "https://circleerp.ru/callback";
		$oauth_client->password_client = 0;
		$oauth_client->personal_access_client = 0;
		$oauth_client->revoked = 0;
		$oauth_client->save();

		// формирование ссылки для запроса авторизации
		$oauth_url = '/oauth/authorize?' . http_build_query([
			'client_id' => $oauth_client->id,
			'redirect_uri' => $oauth_client->redirect,
			'response_type' => 'code',
			'scope' => '',
		]);

		// финальная передача данных
		$data = [
			'step' => 2,
			'oauth_client_redirect' => $oauth_client->redirect,
			'oauth_client_id' => $oauth_client->id,
			'oauth_client_secret' => $oauth_client->secret,
			'oauth_url' => $oauth_url,
			];
		return view('admin.configer.api_token', $data);
		}





	//############## СПИСОК ПОЛЬЗОВАТЕЛЕЙ ##############
	public function getUsers(Request $request) {

		// получение юзеров
		$rows = ConfigerUsers::orderBy('id', 'desc')->get();

		// финальная передача данных
		$data = [
			'rows' => $rows,
			];
		return view('admin.configer.users', $data);
		}





	//############## СПИСОК РОЛЕЙ ##############
	public function getRoles(Request $request) {

		// получение юзеров
		$rows = ConfigerUsersRoles::orderBy('id', 'asc')->get();

		// финальная передача данных
		$data = [
			'rows' => $rows,
			];
		return view('admin.configer.roles', $data);
		}



}
