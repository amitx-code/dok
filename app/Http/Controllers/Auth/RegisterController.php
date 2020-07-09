<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\BillingTariffs;
use App\Libs\Helpers;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    protected $passwordPlain = '';
    protected $confirmToken = '';
    protected $apiToken = '';



    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
	//protected $redirectTo = '/admin/settings/personal/new';




    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest');
    }




    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'oferta' => 'required',
        ]); 
    }




    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
	protected function create(array $data) {
        $this->confirmToken = substr(md5(uniqid('token' . time())), 0, 20);
        $this->apiToken = Str::random(60);
        $tempPassword = "";

		if (Config('auth.global.need_confirm') != true) {
			$this->passwordPlain = substr(md5(uniqid('testo' . time())), 0, 10);
			$tempPassword = bcrypt($this->passwordPlain);
		}

		$referal_phone='';
		if ($data['phone_afillate']) {$ref_id = User::getReferalByPhone($data['phone_afillate']);} // реферрал если телефон
		if (!empty($ref_id)) {$referal_phone = $data['phone_afillate'];}
		if (empty($ref_id)) {$ref_id = Cookie::get('referred');} // получение реферрала из куки
		if (empty($ref_id)) {$ref_id = 0;} // нулевой реферрал используется по умолчанию

		return User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => $tempPassword,
            'confirm_token' => $this->confirmToken,
            'api_token' => $this->apiToken,
			'confirm' => Config('auth.global.need_confirm') ? NULL : 'on',
			'ref_id' => $ref_id,
			'referal_phone' => $referal_phone,
		]);
	}





	protected function registered(Request $request, $user) {
		if (Config('auth.global.need_confirm') == true) {
			$mail = new \App\Mail\UserNeedConfirm($user, $this->confirmToken);
		} else {
			$mail = new \App\Mail\UserRegistered($user, $this->passwordPlain);
		}
		$mail->to($user->email);
		Mail::send($mail);
		return true;
	}





	public function register(Request $request) {
		$this->validator($request->all())->validate(); 

		//отправка емейла юзеру
		event(new Registered($user = $this->create($request->all())));

		//внесение тарифа по умолчанию (всегда "Стандарт")
		$tariffs = config('tariffs');
		Billing::orderTariff($user, $tariffs['STANDARD'], true);

		$this->registered($request, $user);

		// определение куда редиректить после регистрации
		if (Config('auth.global.need_confirm') == true) {
			return response()->json(['url' => '/login?needconfirm']);
		} else {
			$this->guard()->login($user);
//			$this->registered($request, $user);
			return response()->json(['url' => '/admin/settings/personal/new']);
		}

	}




/*############### СТРАНИЦЫ РЕГИСТРАЦИИ ########################*/
	public function getRegistration() {
		$user = Auth::user();
		$data = [
			'user' =>$user,
			'reg' =>1,
		];
		return view('auth.login', $data);
		}

/*############### СТРАНИЦЫ ПОДТВЕРЖДЕНИЯ РЕГИСТРАЦИИ ########################*/
/*	public function getConfirm() {
		$user = Auth::user();
		$data = [
			'user' =>$user,
			'confirm' =>1,
		];
		return view('auth.login', $data);
		} */
	public function getConfirmed() {
		$user = Auth::user();
		$data = [
			'user' =>$user,
//			'confirm' =>2,
		];
		return view('auth.login', $data);
		}


/*####### процедура подтверждения верификации по урлу из письма вида ###############*/
	public function verification(Request $request) {
		$this->passwordPlain = substr(md5(uniqid('testo' . time())), 0, 10);

		// если юзер совсем новый и емейл не подтверждал
		if ($user = User::where('confirm_token', $request->confirmToken) -> where('confirm', null) -> first()) {
			$user->confirm = 'on';
			$user->password = bcrypt($this->passwordPlain);
			$user->save();

			$mail = new \App\Mail\UserRegistered($user, $this->passwordPlain);
			$mail->to($user->email);
			Mail::send($mail);
			return redirect('/login?confirmed');

		// если юзер уже подтвердил себя
		} else if ($user = User::where('confirm_token', $request->confirmToken) -> where('confirm', 'on') -> first()) {
			return redirect('/login?confirmedbefore');

		// если токен для подтверждения не найден
		} else {
			return redirect('/login?notfoundconfirm');
		}

	}



}
