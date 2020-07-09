<?php 

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\Purchaser;
use App\Models\PurchaserCategory;
use App\Models\PurchaserDownload;
use App\Exceptions\PurchaserException;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

class PurchaserController extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;





	/**
     * ##################### ПОКУПКИ И ЧЕКИ - ПОИСК ДОКУМЕНТА #####################
     * @param Request $request
     * @return mixed
     */
	public function getSearch(Request $request) {

	    /** ####### при работе из мобильных приложений ####### */
        if (Route::currentRouteName() == "mobile") {
            $data = [
                'template' => 'admin.template_mobile',
            ];
        }

        /** ####### при работе из веб-сайта ####### */
        else {
            $data = [
                'template' => 'admin.template',
            ];
        }

        return view('admin.purchaser.search', $data);
    }





    //############## ПРОСТЫЕ ТЕКСТОВЫЕ СТРАНИЦЫ ##############
	public function getHelp(Request $request) {
		return view('admin.purchaser.help');
	}





    /**
     * ############## ДОБАВЛИНЕ И ЗАГРУЗКА НОВОГО ДОКУМЕНТА ##############
     */
	public function getAdd(Request $request) {

        if (Route::currentRouteName() == "mobile") {
            $data = [
                'template' => 'admin.template_mobile',
                'urlPrefix' => '/mobile',
                'urlAfter' => '?api_token=' . $request->api_token,
            ];
        }

        /** ####### при работе из веб-сайта ####### */
        else {
            $data = [
                'template' => 'admin.template',
                'urlPrefix' => '',
                'urlAfter' => '',
            ];
        }

        return view('admin.purchaser.add', $data);
	}





	/**
     * ############## АРХИВ ДОКУМЕНТОВ ##############
     */
	public function getList(Request $request) {

		// категории документов для верхних вкладок
		$categories = PurchaserCategory::orderBy('sort')->get();
		$category = PurchaserCategory::find($request->category_id);

		// получение документов
		$documents = $category->getFindDocsAttribute();

        /** ####### при работе из мобильных приложений ####### */
        if (Route::currentRouteName() == "mobile") {
            $data = [
                'template' => 'admin.template_mobile',
                'urlPrefix' => '/mobile',
                'urlAfter' => '?api_token=' . $request->api_token,
                'searchString' => '',
                'categories' => $categories,
                'categoryId' => $category->id ?? 0,
                'documents' => $documents,
            ];
        }

        /** ####### при работе из веб-сайта ####### */
        else {
            $data = [
                'template' => 'admin.template',
                'urlPrefix' => '',
                'urlAfter' => '',
                'searchString' => '',
                'categories' => $categories,
                'categoryId' => $category->id ?? 0,
                'documents' => $documents,
            ];
        }

		return view('admin.purchaser.list', $data);
	}




    /**
     * ############## ПОИСК РЕЗУЛЬТАТЫ (работает точно так же как СПИСОК ВСЕХ ПАРТНЕРОВ) ##############
     */
	public function postSearch(Request $request) {

		// категории документов для верхних вкладок
		$categories = PurchaserCategory::orderBy('sort')->get();
		$category = PurchaserCategory::find($request->category_id);

		// получение документов
		$documents = $category->getFindDocsAttribute();

        /** ####### при работе из мобильных приложений ####### */
        if (Route::currentRouteName() == "mobile") {
            $data = [
                'template' => 'admin.template_mobile',
                'urlPrefix' => '/mobile',
                'urlAfter' => '?api_token=' . $request->api_token,
                'searchString' => $request->searchString,
                'categories' => $categories,
                'categoryId' => $category->id ?? 0,
                'documents' => $documents,
            ];
        }

        /** ####### при работе из веб-сайта ####### */
        else {
            $data = [
                'template' => 'admin.template',
                'urlPrefix' => '',
                'urlAfter' => '',
                'searchString' => $request->searchString,
                'categories' => $categories,
                'categoryId' => $category->id ?? 0,
                'documents' => $documents,
            ];
        }

        return view('admin.purchaser.list', $data);
	}




    /**
     * ############## ПОКАЗ КАРТОЧКИ ДОКУМЕНТА ##############
     */
	public function getCard(Request $request) {
		$user = Auth::user();

		// простейшие проверки
		$document = Purchaser::find($request->id);
		if (!$document) {
			return redirect('/admin/purchaser/list');
		}

		// получение имени автора документа и его прав доступа
		$temp = User::find($document->user_id);
		$document->user_name = $temp->name;
		$document->user_role_id = $temp->role_id;

        if (Route::currentRouteName() == "mobile") {
            $data = [
                'template' => 'admin.template_mobile',
                'urlPrefix' => '/mobile',
                'urlAfter' => '?api_token=' . $request->api_token,
                'document' => $document,
            ];
        }

        /** ####### при работе из веб-сайта ####### */
        else {
            $data = [
                'template' => 'admin.template',
                'urlPrefix' => '',
                'urlAfter' => '',
                'document' => $document,
            ];
        }

        return view('admin.purchaser.list_card', $data);
	}




	//############## СКАЧИВАНИЕ ДОКУМЕНТА  ##############
	public function getDownload(Request $request) {
		$user = Auth::user();

		// простейшие проверки
		$document = Purchaser::find($request->id);
		if (!$document) {
			return redirect('/admin/purchaser/list');
		}

		// получение имени автора документа и его прав доступа
		$temp = User::find($document->user_id);
		$document->user_name = $temp->name;
		$document->user_role_id = $temp->role_id;

		// проверка доступа к документу
		if (($user->id == $document->user_id) || ($user->role_id > $document->user_role_id)) {}
		else {
			return redirect('/admin/purchaser/list/card/' . $document->id);
		}

		// скачивание файла
		$array = PurchaserDownload::downloadDocument($document, $request->action);
		if ($array) {
			return response()->download($array[0], $array[1], $array[2]);
		}
		else {
			return redirect('/admin/purchaser/list');
		}
	}



}
