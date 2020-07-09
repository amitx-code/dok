<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
//use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers; 
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }








	/*################## если логин успешно прошёл, но ещё ДО отправки пользователю события ####*/
	protected function authenticated(Request $request, $user) {
		if (Config('auth.global.need_confirm') == true && $user->confirm != 'on') {
			$this->registeredNoConfirm($request, $user);
			Auth::logout();
			//$request->session()->regenerateToken();
			return response()->json(['redirect' => '/login?needconfirm'], 422);

			throw ValidationException::withMessages([
				'redirect' => '/login?needconfirm',
			]);
		}

		// при логине из мобильных приложений
		if (Route::currentRouteName() == "mobile") {

			/* это пригодится потом в других модулях когда обращаются за запросом
			if ($request->session) {
			    $session=addslashes($request->session);
			    $_COOKIE['PHPSESSID'] = $session;
			    $_SESSION['PHPSESSID'] = $session;
			    $minutes = 60*24*30;
			    Cookie::queue('PHPSESSID', $session, $minutes);
			} */

			$session = $user->id . ":" . substr(md5(uniqid('1st' . time())), 0, 32) . ":" . substr(md5(uniqid('2nd' . time())), 0, 32);
            $minutes = 60*24*30*12; // на год
			Cookie::queue('PHPSESSID', $session, $minutes);
			$_SESSION['PHPSESSID'] = $session;

            if (!$user->api_token) {
                $user->api_token = Str::random(60);
            }
            $user->save();

            return response()->json([
                'session' => $session,
				'time' => time(),
				'error' => 0,
				'errorMessage' => '',
				'data' => [
					'session' => $session,
					'user' => [
						'id' => $user->id,
                        'api_token' => $user->api_token, // MRNX: используется в моб приложении
						'login' => $user->email,
						'email' => $user->email,
						'_name' => $user->name,
						'name' => $user->name,
						'username' => $user->name,
						'_url' => "https://getlaw.ru",
						'create_time' => $user->created,
						'lang' => 'ru', // важно для моб.приложения
						'delete' => '',
						'country' => $user->country,
						'country_ru' => $user->country, // потом переделать на языки
						'region' => '',
						'region_ru' => '',
						'city' => $user->city,
						'city_ru' => $user->city, // потом переделать на языки
						'address' => $user->address,
						'phone' => $user->phone,
						'company' => '',
						'position' => '',
						'sex' => $user->sex,
						'avatar' => 'https://getlaw.ru/img_public/test_avatar.jpg', // потом заменить
						'real' => '',
						'birthday' => '',
						'age' => 0,
						'height' => 0,
						'weight' => 0,
						'is_moderator' => '',
						'code' => '',
						'unique' => 'e581cc0bf16f697c6e2446b7146d7d61', // потом заменить
						'bann_unique' => '',
						'moderator' => '',
						'rating' => 0,
						'relogin' => '',
						'text' => '',
						'displayname' => $user->name . " " . $user->name_patronymic . " " . $user->name_family,
						'discount_cumulative' => '',
						'discount_percent' => '',
						'discount_rur' => '',
						'discount_usd' => '',
						'discount_eur' => '',
						'confirmed' => true, // важно для моб.приложения
						'photos' => [],
						'photosCount' => 0,
						'billing' => 999999, // биллинг добавить сюда
					],
					'globalstat' => [
						'active_total' => 999,
						'user_total' => 999,
						'photo_total' => 999,
						'love_total' => 999,
						'traveler_total' => 999,
						'traveler_total' => 999,
						'soderzhanki_total' => 999,
						'friends_total' => 999,
						'job_total' => 999,
						'blog_total' => 999,
						'users_total' => 999,
						'real-love_total' => 999,
						'bq-new-year-2018_total' => 999,
					]
				],
			]);





		// при логине из веб-сайта
		} else {
			return response()->json(['redirect' => '/admin'], 422);
		}
	}
	/*############################################################################*/








	/*################## отправка мейла если человек зарегистрирован но ещё не подтвердил себя ####*/
	protected function registeredNoConfirm(Request $request, $user) { 
		$mail = new \App\Mail\UserNeedConfirm($user, $user->confirm_token);
		$mail->to($user->email);
		Mail::send($mail);
		return true;
	}
	/*############################################################################*/







	/*############### при неудачной аутентификации пользователя ########################*/
	protected function sendFailedLoginResponse(Request $request) {

		// при логине из мобильных приложений
		if (Route::currentRouteName() == "mobile") {
			return response()->json([
				'time' => time(),
				'error' => 1,
				'errorMessage' => 'Неправильный логин и/или пароль',
				'data' => [
					'ok' => 'empty',
				],
				'session' => '',
				'debug' => [
					'request' => $request,
				],
			], 401);

		// при логине из веб-сайта
		} else {
			return response()->json([
				'message' => 'The given data was invalid.',
				'errors' => [
					'email' => 'Неправильный логин и/или пароль',
				],
			], 401);
		}

	}
	/*############################################################################*/









	/*
	// возможно пригодится если нужно будет логиниться с проверкой каких-нибудь жестких параметров
	public function credentials(Request $request) {
		$credentials = $request->only($this->username(), 'password');
		$credentials = array_add($credentials, 'confirm', 'on');
		return $credentials;
	}

	// это ответ после успешной аутентификации пользователя, может быть пригодится потом
	protected function sendLoginResponse(Request $request) {
		$this->clearLoginAttempts($request);
		return response()->json(['url' => $this->redirectPath()]);
	}
	*/










	/*############### СТАТИЧНЫЕ СТРАНИЦЫ ЛОГИНА ########################*/
	public function getLogin() {
		$user = Auth::user();
		$data = [
			'user' =>$user,
		];
		return view('auth.login', $data);
		}
	/*############################################################################*/







}
