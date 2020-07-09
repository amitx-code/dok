<?php

namespace App\Http\Controllers;

use App\Libs\Reports;
use App\Models\BillingTariffs;
use App\Models\User;

use App\Models\Partnerer;
use App\Models\PartnererTerritory;

//use App\Exceptions\BillingException;
//use App\Exceptions\OrdersException;
use App\Models\Billing;
use App\Models\Rating;
//use App\Models\BillingInvoice;
//use App\Models\Orders;
//use App\Models\Test;
//use App\Models\TestCategory;
//use App\Models\TestSession;
//use App\Libs\Robokassa;
use App\Rules\Phone; // для валидации телефонов
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class AdminController extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



	//############## ГЛАВНАЯ КАБИНЕТА ##############
	public function getIndex(Request $request) {
		$user = Auth::user();
		$tariffs = config('tariffs');

		$balance = Billing::getBalance($user->id, true); // TODO: убрать потом очистку кэша!
		$stats = Billing::getBalanceMonths($user->id, 6, 0, true); // TODO: убрать потом очистку кэша!
		$stats_tariffs = Billing::getTotalBonusesTariffs($user->id, true); // TODO: убрать потом очистку кэша!
        $rating = Rating::getUserRating($user->id, true); // TODO: убрать потом очистку кэша!
		$leaders = Billing::getLeaders(3, true); // TODO: убрать потом очистку кэша!

		$data = [
			'request' => $request,
			'user' =>$user,
			'balance' => $balance,
			'tariffs' => $tariffs,
			'stats' => $stats,
			'stats_tariffs' =>$stats_tariffs,
			'rating' =>$rating,
			'leaders' =>$leaders,
		];
		return view('admin.main', $data);
		}




	//############## ПРОСТЫЕ ТЕКСТОВЫЕ СТРАНИЦЫ ##############
	public function getTest(Request $request) {
		$user = Auth::user();

		$billing = new Billing();
		$data = $billing->payPercentsToReferrals(43, 10000);


		exit;

	    // $user = User::find(39);
/*

	    $users = User::get();
	    $cnt = 0;
	    foreach ($users as $user) {
	        $tariff = BillingTariffs::where('user_id', $user->id)->orderBy('start', 'desc')->first();
	        if ($tariff) {
                print "user: $user->id, tariff: $tariff->tariff\n";
                $user->tariff =  $tariff->tariff;
                $user->save();
            }
        }

	    print $cnt;
	    exit;
*/
	    $levels = $user->getSubUsers(8);
	    var_dump($levels);

	    exit;

		return view('admin.test');
		}
	public function getHelp(Request $request) {
		return view('admin.settings.help');
		}

	//############## ПРОСТЫЕ ТЕКСТОВЫЕ СТРАНИЦЫ support ##############
	public function getSupportService(Request $request) {
		return view('admin.support.service');
	}
	public function getSupportFaq(Request $request) {
		return view('admin.support.faq');
	}
	public function getSupportNews(Request $request) {
		return view('admin.support.news');
	}
	public function getSupportContacts(Request $request) {
	    return view('admin.support.contacts');
	}
	public function getSupportLearning(Request $request) {
		return view('admin.support.learning');
	}
	public function getSupportDocs(Request $request) {
		return view('admin.support.docs');
	}




	//############## НОВОСТИ ##############
	public function getNewsList(Request $request) {
		return view('admin.news.list', ['user' =>  Auth::user()]);
		}
	public function getNews(Request $request) {
		$blade = "admin.news." . $request->y . "." . $request->m . "." . $request->d . "." . $request->file;
		return view($blade, ['user' =>  Auth::user()]);
		}




	//############## СТРАНИЦА ПАРТНЕРСКИХ ПРОГРАММ ##############
	public function getPartnership(Request $request) {
		$user = Auth::user();
		$tariffs = config('tariffs');
		$data = [
			'user' => $user,
			'referal' => $user->getReferalCode(),
			'tariffs' => $tariffs,
       	];
		return view('admin.afillater.partnership', $data);
	}






/*############### ЛИЧНЫЕ ДАННЫЕ И ИХ ИЗМЕНЕНИЕ #############################*/
public function getSettings(Request $request) {

	$user = Auth::user();

		// территории документов для выбора города пользователя
		$territories = PartnererTerritory::orderBy('name')->get();
		$territory = PartnererTerritory::find($request->territory_id);
		$territoryId = $territory->id ?? 0;

		$data = [
			'territories' => $territories,
			'territoryId' => $territoryId,
			'request' => $request,
			'user' => $user,
		];

	return view('admin.settings.personal', $data);
 }

public function postSettings(Request $request) {
        $request->validate([
            'name' => ['required'],
            'name_patronymic' => ['required'],
            'name_family' => ['required'],
            'sex' => ['required'],
            'city' => ['required'],
            'phone' => ['required', new Phone],
            'email' => ['required', 'email'],
            'currency' => ['required'],
       ]);

        $user = Auth::user();
        foreach(['name', 'name_patronymic', 'name_family', 'sex', 'city', 'phone', 'email', 'currency'] as $key) {
            $user->$key = $request->$key ?? '';
        }
        $user->save();

        return response()->json(['result' => 'success']);
}

public function postChangePassword(Request $request) {
        $request->validate([
            'password' => 'required|confirmed',
        ], ['password.confirmed' => 'Введенные пароли не совпадают']);

        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['result' => 'success']);
}

public function postChangeAddress(Request $request) {
        $request->validate([
            'country' => ['required'],
            'zip' => ['required'],
            'city' => ['required'],
            'address' => ['required'],
 	]);

        $user = Auth::user();
        foreach(['country', 'zip', 'city', 'address'] as $key) {
            $user->$key = $request->$key ?? '';
        }
        $user->save();

        return response()->json(['result' => 'success']);
    }

public function postChangeAvatar(Request $request) {
	$request->validate([
	'avatar' => ['required', 'mimes:jpg,jpeg,gif,png', 'max:5000', 'dimensions:min_width=500,min_height=500'],
	]);

	$user = Auth::user();

	foreach(['avatar'] as $key) {
		$filenameWithExt = $request->file($key)->getClientOriginalName();
		$filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
		$extension = $request->file($key)->getClientOriginalExtension();
		//$fileNameToStore = $user->id . "_" . $key . '.' . $extension;
		$fileNameToStore = md5(microtime().rand(0, 9999)) . '.' . $extension;
		$path = $request->file($key)->storeAs('avatars', $fileNameToStore);
		$user->$key = $fileNameToStore ?? '';
	}

	// создание квадратных аватарок и сохранение их на диск
	$filePatch = storage_path().'/app/avatars/';
	$fileLoad = $filePatch.$fileNameToStore;
	$manager = new ImageManager(array('driver' => 'imagick'));
	$image = $manager->make($fileLoad)->fit(48, 48)->save($filePatch."48x48.".$fileNameToStore, 100);
	$image = $manager->make($fileLoad)->fit(150, 150)->save($filePatch."150x150.".$fileNameToStore, 100);
	$image = $manager->make($fileLoad)->fit(500, 500)->save($filePatch."500x500.".$fileNameToStore, 100);

	$user->save();
//	return response()->json(['redirect' => '/admin/settings/personal'], 422);
	return response()->json(['result' => 'success']);
}

public function postChangeAfillate(Request $request) {
	$request->validate([
		'phone_afillate' => ['required', new Phone],
	]);

	$ref_id = User::getReferalByPhone($request->phone_afillate);
	if (empty($ref_id)) {
		return response()->json(['result' => 'notfound']);
	}

	$user = Auth::user();
	$user->ref_id = $ref_id;
	$user->referal_phone = $request->phone_afillate;
	$user->save();

	return response()->json(['result' => 'success']);
}
/*###############  ///  ##################################################*/










/*############### ДАННЫЕ ТСП ##################################################*/
	public function getTsp(Request $request) {
	
		$data = [
			'request' => $request,
			'user' => Auth::user(),
		];
	
		return view('admin.settings.tsp', $data);
	}
	
/*############### ПОЛУЧЕНИЕ ФАЙЛА ТСП ##################################################*/
	public function getTspFile(Request $request) {

		$user = Auth::user();

		if (!empty($request->file) && Storage::exists("docs_tsp/" . $request->file)) {
			$test = explode("_", $request->file);
			if ($test[0] == $user->id) {
				return response()->download(storage_path("app/docs_tsp/" . $request->file));
			}
		}
		return response(404);
	}

/*############### ДАННЫЕ ТСП ИЗМЕНЕНИЕ ##################################################*/
	public function postTsp(Request $request) {

		$user = Auth::user();

		/*#### Сохранение формы с реквизитами #########*/
		if ($request->part == '1') {

			/*#### Для юрлиц #########*/
			if (empty($request->type)) {
				$request->validate([
					'tsp_name' => ['required'],
					'tsp_name_short' => ['required'],
					'tsp_inn' => ['required', 'size:10'],
					'tsp_kpp' => ['required', 'size:9'],
					'tsp_ogrn' => ['required', 'size:13'],
					'tsp_okved' => ['required'],
					'tsp_okpo' => ['required', 'min:8', 'max:14'],
					'tsp_address_post' => ['required'],
					'tsp_address_ur' => ['required'],
					'tsp_email' => ['required', 'email'],
					'tsp_director' => ['required'],
					'tsp_bank' => ['required'],
				]);
				foreach(['tsp_name', 'tsp_name_short', 'tsp_inn', 'tsp_kpp', 'tsp_ogrn', 'tsp_okved', 'tsp_okpo', 'tsp_address_post', 'tsp_address_ur', 'tsp_email', 'tsp_director', 'tsp_bank'] as $key) {
					$user->$key = $request->$key ?? '';
				}

			/*#### Для ИП #########*/
			} else if (!empty($request['type']) && $request['type']=='2') {
				$request->validate([
					'tsp_name' => ['required'],
					'tsp_inn' => ['required', 'size:10'],
					'tsp_ogrn' => ['required', 'size:15'],
					'tsp_address_ur' => ['required'],
					'tsp_email' => ['required', 'email'],
					'tsp_bank' => ['required'],
				]);
				foreach(['tsp_name', 'tsp_inn', 'tsp_ogrn', 'tsp_address_ur', 'tsp_email', 'tsp_bank'] as $key) {
					$user->$key = $request->$key ?? '';
				}

			}
			/*##########*/

		}

		/*#### Сохранение формы с документами #########*/
		else if ($request->part == '2') {

			/*#### Для юрлиц #########*/
			if (empty($request->type)) {
				$request->validate([
					'file_ogrn' => ['required', 'mimes:pdf,jpg,jpeg,png,doc,docx,zip', 'max:5000'],
					'file_ifns' => ['required', 'mimes:pdf,jpg,jpeg,png,doc,docx,zip', 'max:5000'],
					'file_prikaz' => ['required', 'mimes:pdf,jpg,jpeg,png,doc,docx,zip', 'max:5000'],
					'file_arenda' => ['required', 'mimes:pdf,jpg,jpeg,png,doc,docx,zip', 'max:5000'],
					'file_dover' => ['mimes:pdf,jpg,jpeg,png,doc,docx,zip', 'max:5000'],
					'file_ustav' => ['required', 'mimes:pdf,jpg,jpeg,png,doc,docx,zip', 'max:5000'],
					'file_karta' => ['required', 'mimes:pdf,jpg,jpeg,png,doc,docx,zip', 'max:5000'],
				]);
				foreach(['file_ogrn', 'file_ifns', 'file_prikaz', 'file_arenda', 'file_dover', 'file_ustav', 'file_karta'] as $key) {
					$filenameWithExt = $request->file($key)->getClientOriginalName();
					$filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
					$extension = $request->file($key)->getClientOriginalExtension();
					$fileNameToStore = $user->id . "_" . $key . '.' . $extension;
					$path = $request->file($key)->storeAs('docs_tsp', $fileNameToStore);
					$user->$key = $fileNameToStore ?? '';
				}

			/*#### Для ИП #########*/
			} else if (!empty($request['type']) && $request['type']=='2') {
				$request->validate([
					'file_ogrn' => ['required', 'mimes:pdf,jpg,jpeg,png,doc,docx,zip', 'max:5000'],
					'file_ifns' => ['required', 'mimes:pdf,jpg,jpeg,png,doc,docx,zip', 'max:5000'],
					'file_arenda' => ['required', 'mimes:pdf,jpg,jpeg,png,doc,docx,zip', 'max:5000'],
					'file_karta' => ['required', 'mimes:pdf,jpg,jpeg,png,doc,docx,zip', 'max:5000'],
				]);

				foreach(['file_ogrn', 'file_ifns', 'file_arenda', 'file_karta'] as $key) {
					$filenameWithExt = $request->file($key)->getClientOriginalName();
					$filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
					$extension = $request->file($key)->getClientOriginalExtension();
					$fileNameToStore = $user->id . "_" . $key . '.' . $extension;
					$path = $request->file($key)->storeAs('docs_tsp', $fileNameToStore);
					$user->$key = $fileNameToStore ?? '';
				}

			}
			/*##########*/
	
		}
	
		$user->save();
		return response()->json(['result' => 'success']);
	}



    public function getReports(Request $request) {
	    $reports = new Reports();
	    $data = $reports->getTariffReport(Auth::user()->id);

        return view('admin.reports.index', $data);
    }




}
