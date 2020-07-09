<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class ReminderController extends Controller 
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
//    protected $confirmToken = '';



    /**
     * Where to redirect users after registration.
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
            //$this->middleware('guest');
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
            'email' => 'required|string|email|max:255',
        ]);
    }



	// проверка юзера-емейла на существование
	protected function checkUser(array $data) {
		$temp = User::where('email', $data['email'])->limit(1)->get();
		if (count($temp)>0) {
			$user = $temp[0];
	        return $user;
			}
		return false;
	}


	// проверка подтвержден ли был юзера при регистрации (если требуется подтверждение емейла)
	protected function checkConfirm(array $data) {
		$temp = User::where('email', $data['email']) -> where('confirm', 'on') -> limit(1)->get();
		if (count($temp)>0) {
			$user = $temp[0];
	        return $user;
			}
		return false;
	}





    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
	protected function create(array $data) {
		$this->passwordPlain = substr(md5(uniqid('testo' . time())), 0, 10);

		return User::where('email', $data['email'])->update([
			'password' => bcrypt($this->passwordPlain),
		]);
	}

/*
	protected function createNoConfirm(array $data) {
		$this->confirmToken = substr(md5(uniqid('token' . time())), 0, 20);

		return User::where('email', $data['email'])->update([
			'confirm_token' => $this->confirmToken,
		]);
	}
*/




	protected function registered(Request $request, $user) { 
		$mail = new \App\Mail\ResetPassword($user, $this->passwordPlain);
		$mail->to($user->email);
		Mail::send($mail);
		return true;
	}

	protected function registeredNoConfirm(Request $request, $user) { 
		$mail = new \App\Mail\UserNeedConfirm($user, $user->confirm_token);
		$mail->to($user->email);
		Mail::send($mail);
		return true;
	}




    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

		if (!$user = $this->checkUser($request->all())) {
			return response()->json(['result' => 'notfound']);
			}

		if (!$user = $this->checkConfirm($request->all())) {
			$user = $this->checkUser($request->all()); // получили юзера только по мейлу
//			$this->createNoConfirm($request->all());
			$this->registeredNoConfirm($request, $user);
	   		return response()->json(['url' => '/login?needconfirm']);
			}

		//return response()->json(['user' => $user]);

		$this->create($request->all());
		$this->registered($request, $user);

//        return response()->json(['result' => 'success']);
	    return response()->json(['url' => '/login?reminded']);
    }





/*############### СТРАНИЦЫ РЕМАЙНДЕРА ########################*/
	public function getReminder() {
		$user = Auth::user();
		$data = [
			'user' =>$user,
			'reminder' =>1,
		];
		return view('auth.login', $data);
		}



}
