<?php
/*
Ulogin.ru авто-регистрация или логин через соцсети
Сделано по статье https://habr.com/ru/post/320046/
*/

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Libs\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Intervention\Image\ImageManager;

class UloginController extends Controller
{






// Login user through social network.
    public function login(Request $request)
    {

		// Get information about user.
		$data = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
		$user = json_decode($data, TRUE);
		$network = $user['network'];




		// Поисек юзера в базе
		$userData = User::where('email', $user['email'])->first();





		// если юзера найдер, то логинимся
		if (isset($userData->id)) {

			// Make login user
			Auth::loginUsingId($userData->id, TRUE);
			
			return redirect('/admin');
		}





		// если юзер не найден, то регистрируем его
		else {

			$confirmToken = substr(md5(uniqid('token' . time())), 0, 20);
			$passwordPlain = substr(md5(uniqid('testo' . time())), 0, 10);
			$tempPassword = bcrypt($passwordPlain);

			// Преобразования полученных форматов в наш формат
			if (empty($user['first_name'])) 	{$user['first_name'] = '';}
			if (empty($user['last_name'])) 	{$user['last_name'] = '';}
			if (empty($user['sex'])) 			{$user['sex'] = '';}
			elseif ($user['sex']=='1')			{$user['sex'] = 'F';}
			elseif ($user['sex']=='2')			{$user['sex'] = 'M';}
			if (empty($user['phone'])) 		{$user['phone'] = '';}
			else {$user['phone'] = Helpers::PhoneStandard01( $user['phone'] );}
			if (empty($user['bdate'])) 		{$user['bdate'] = '';}
			if (empty($user['city'])) 			{$user['city'] = '';}
			if (empty($user['country'])) 		{$user['country'] = '';}
			if (empty($user['network'])) 		{$user['network'] = '';}
			if (empty($user['profile'])) 		{$user['profile'] = '';}

			// Скачивание аватара и сохранение (если он есть)
			if (empty($user['photo_big'])) 	{$fileNameToStore = '';}
			else {

				$extension = preg_replace( "/.*\.([^\.\/]+)$/", "$1", $user['photo_big'] );
				if ($extension == $user['photo_big']) {$extension = "jpg";}
				$fileNameToStore = md5(microtime().rand(0, 9999)) . '.' . $extension;
				$filePatch = storage_path().'/app/avatars/';

				$ch = curl_init ( $user['photo_big'] );
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
				$raw=curl_exec($ch);
				curl_close ($ch);
				if(file_exists($filePatch.$fileNameToStore)){unlink($filePatch.$fileNameToStore);}
				$fp = fopen($filePatch.$fileNameToStore,'x');
				fwrite($fp, $raw);
				fclose($fp);

				// создание квадратных аватарок и сохранение их на диск
				$filePatch = storage_path().'/app/avatars/';
				$fileLoad = $filePatch.$fileNameToStore;
				$manager = new ImageManager(array('driver' => 'imagick'));
				$image = $manager->make($fileLoad)->fit(48, 48)->save($filePatch."48x48.".$fileNameToStore, 100);
				$image = $manager->make($fileLoad)->fit(150, 150)->save($filePatch."150x150.".$fileNameToStore, 100);
				$image = $manager->make($fileLoad)->fit(500, 500)->save($filePatch."500x500.".$fileNameToStore, 100);
			}

			// определение реферрала
			$ref_id = Cookie::get('referred'); // получение реферрала из куки
			if (empty($ref_id)) {$ref_id = 0;} // нулевой реферрал используется по умолчанию

			// Create new user in DB
			$newUser = User::create([
				'email' => $user['email'],
				'name' => $user['first_name'],
				'name_family' => $user['last_name'],
				'sex' => $user['sex'],
				'avatar' => $fileNameToStore,
				'phone' => $user['phone'],
				//'birthday' => $user['bdate'], // у нас в базе пока не испольуется
				//'city' => $user['city'], // у нас в базе пока не испольуется
				'country' => $user['country'],

				'login_social_network' => $user['network'],
				'login_social_url' => $user['profile'],

				'password' => $tempPassword,
				'confirm_token' => $confirmToken,
				'confirm' => 'on',
				'ref_id' => $ref_id,
			]);

			// Make login user
			Auth::loginUsingId($newUser->id, TRUE);
			
			return redirect('/admin');
		}


	}





}