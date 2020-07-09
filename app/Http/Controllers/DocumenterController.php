<?php 

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\Documenter;
use App\Models\DocumenterCategory;
use App\Models\DocumenterDownload;
use App\Exceptions\DocumenterException;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Config;



class DocumenterController extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



	//############## ПРОСТЫЕ ТЕКСТОВЫЕ СТРАНИЦЫ ##############
	public function getHelp(Request $request) {
		return view('admin.documenter.help');
		}
	public function getSettings(Request $request) {
		return view('admin.documenter.settings');
		}



	//############## АРХИВ ДОКУМЕНТОВ ##############
	public function getList(Request $request) {

		// категории документов для верхних вкладок
		$categories = DocumenterCategory::orderBy('sort')->get();
		$category = DocumenterCategory::find($request->category_id);

		// получение документов
		$documents = $category->FindDocs;

		// финальная передача данных
		$data = [
			'categories' => $categories,
			'categoryId' => $category->id ?? 0,
			'documents' => $documents,
			];
		return view('admin.documenter.list', $data);
		}



	//############## ПОКАЗ КАРТОЧКИ ДОКУМЕНТА  ##############
	public function getCard(Request $request) {
		$user = Auth::user();

		// простейшие проверки
		$document = Documenter::find($request->id);
		if (!$document) {
			return redirect('/admin/documenter/list');
		}

		// получение имени автора документа и его прав доступа
		$temp = User::find($document->user_id);
		$document->user_name = $temp->name;
		$document->user_role_id = $temp->role_id;

		// финальная передача данных
		$data = [
			'user' => $user,
			'document' => $document,
		];
		return view('admin.documenter.list_card', $data);
	}




	//############## СКАЧИВАНИЕ ДОКУМЕНТА  ##############
	public function getDownload(Request $request) {
		$user = Auth::user();

		// простейшие проверки
		$document = Documenter::find($request->id);
		if (!$document) {
			return redirect('/admin/documenter/list');
		}

		// скачивание файла
		$array = DocumenterDownload::downloadDocument($document, $request->action);
		if ($array) {
			return response()->download($array[0], $array[1], $array[2]);
		}
		else {
			return redirect('/admin/documenter/list');
		}
	}



}
