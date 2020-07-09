<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\Templater;
use App\Models\TemplaterCategory;
use App\Models\TemplaterTemplates;
use App\Models\TemplaterFields;
use App\Models\TemplaterBoxes;
use App\Models\TemplaterGenerate;
use App\Exceptions\TemplaterException;

use App\Models\Documenter;
use App\Models\DocumenterCategory;
use App\Exceptions\DocumenterException;

use App\Libs\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Config;

use PhpOffice\PhpWord\Reader\Word2007;


class TemplaterController extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;




	//############## ПРОСТЫЕ ТЕКСТОВЫЕ СТРАНИЦЫ ##############
	public function getHelp(Request $request) {
		return view('admin.templater.help');
		}
	public function getSettings(Request $request) {
		return view('admin.templater.settings');
		}




	//############## СПИСОК ШАБЛОНОВ ПРИ СОЗДАНИИ НОВОГО ДОКУМЕНТА ##############
	public static function getAdd(Request $request) {

		// категории документов для верхних вкладок
		$categories = TemplaterCategory::orderBy('sort', 'asc')->get();
		$category = $request->category_id ? TemplaterCategory::find($request->category_id) : false;
		if ($category) {
			$templates = $category->FindTemplates;
		} else {
			$templates = TemplaterTemplates::orderBy('name', 'asc')->get();
		} 

		// вывод для API
		if (($request->route()->getAction())['middleware'][0] == "api") {
			if (request('api_essence') == "categories") {
				Helpers::ApiOutput($categories, request('api_format') );
			} else {
				Helpers::ApiOutput($templates, request('api_format') );
			}

		// вывод для веб-админки
		} else {
			$data = [
				'categories' => $categories,
				'categoryId' => $category->id ?? 0,
				'templates' => $templates,
			];
			return view('admin.templater.add', $data);
		}
	}




    /**
     * ############## СТАРТ СОЗДАНИЯ НОВОГО ДОКУМЕНТА В ТАБЛИЧНОМ ВИДЕ ##############
     * @param Request $request
     * @return mixed
     */
	public static function getAddStart(Request $request) { 

		// простейшие проверки
		$template = TemplaterTemplates::find($request->id);
		if (!$template) {
			return redirect('/admin/templater/add');
		}

		// получение всех полей документа
		$fields = TemplaterFields::where('template_id', $request->id)->orderBy("sort", "asc")->get(); // почему-то не рабоает sort, временно отключено
		$fields = TemplaterFields::where('template_id', $request->id)->orderBy("id", 'asc')->get();

		// получение всех боксов для документа
		$boxes = TemplaterBoxes::where('template_id', $request->id)->orderBy("sort", 'asc')->get();

		// получение сохраненных данных другого документа если создание происходит на его основе
		if (is_numeric($request->fill)) {
			$exampleSaved = Templater::find($request->fill);
			if (!$exampleSaved) {
				return redirect('/admin/templater/add');
			}
			$example = unserialize($exampleSaved->array);
		} else {
			$example = null;
		}

		// вывод для API
		if (($request->route()->getAction())['middleware'][0] == "api") {
			Helpers::ApiOutput($fields, request('api_format') );

		// вывод для веб-админки
		} else {
			$data = [
				'template' => $template,
				'example' => $example,
				'fields' => $fields,
				'boxes' => $boxes,
				'request' => $request,
			];
			return view('admin.templater.add_start', $data);
		}
    }



    /**
     * ############## СТАРТ СОЗДАНИЯ НОВОГО ДОКУМЕНТА В ВИЗУАЛЬНОМ ВИДЕ ##############
     * @param Request $request
     * @return mixed
     */
    public static function getAddStartvis(Request $request) {

        // простейшие проверки
        $template = TemplaterTemplates::find($request->id);
        if (!$template) {
            return redirect('/admin/templater/add');
        }

        // получение всех полей документа
        $fields = TemplaterFields::where('template_id', $request->id)->orderBy("sort", "asc")->get(); // почему-то не рабоает sort, временно отключено
        $fields = TemplaterFields::where('template_id', $request->id)->orderBy("id", 'asc')->get();

        // получение всех боксов для документа
        $boxes = TemplaterBoxes::where('template_id', $request->id)->orderBy("sort", 'asc')->get();

        // получение сохраненных данных другого документа если создание происходит на его основе
        if (is_numeric($request->fill)) {
            $exampleSaved = Templater::find($request->fill);
            if (!$exampleSaved) {
                return redirect('/admin/templater/add');
            }
            $example = unserialize($exampleSaved->array);
        } else {
            $example = null;
        }


        /**
         * получение текста самого документа, для вывода в визуальном виде
         */
        $fileTemplate = $_SERVER['DOCUMENT_ROOT'].'/../resources/doc_templates/' . preg_replace('/\.docx/', '.html', $template->filename);
        $doctext = file_get_contents($fileTemplate);
        $pattern = Array(
            '/<a\s[^>]*?\>/si',
            '/<\/*html>/si',
            '/<\/*head>/si',
            '/<meta\s.*?\>/si',
            '/<\/*body\s.*?\>/si',
            '/<style[^>]*?>.*?<\/style>/si',
            '/<script[^>]*?>.*?<\/script>/si',
            '/<\/*span[^>]*?\>/si',
            '/\(SHORT\)/si',
            '/\$\{([^\}]+)\}/s',
        );
        $replacement = Array(
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '<span class="field field_$1">НЕЗАПОЛНЕННОЕ&nbsp;ПОЛЕ</span>',
        );
        $doctext = preg_replace($pattern, $replacement, $doctext);
        /** */


        // вывод для API
        if (($request->route()->getAction())['middleware'][0] == "api") {
            Helpers::ApiOutput($fields, request('api_format') );

            // вывод для веб-админки
        } else {
            $data = [
                'template' => $template,
                'example' => $example,
                'fields' => $fields,
                'boxes' => $boxes,
                'request' => $request,
                'doctext' => $doctext,
            ];
            return view('admin.templater.add_startvis', $data);
        }
    }





    //############## ВЫБОР ДАННЫХ ДРУГОГО ДОКУМЕНТА ДЛЯ СОЗДАНИЯ НОВОГО ДОКУМЕНТА ##############
	public function getAddExample(Request $request) { 

		// простейшие проверки
		$template = TemplaterTemplates::find($request->id);
		if (!$template) {
			return redirect('/admin/templater/add');
		}

		// категории документов для верхних вкладок
		$categories = DocumenterCategory::orderBy('sort', 'asc')->get();
		$category = DocumenterCategory::find($request->category_id);

		// получение документов
		$documents = $category->FindDocsFromTemplates;

		// финальная передача данных
		$data = [
			'template' => $template,
			'categories' => $categories,
			'categoryId' => $category->id ?? 0,
			'documents' => $documents,
			];
		return view('admin.templater.add_example', $data);
    }




	//############## ГЕНЕРАЦИЯ ДОКУМЕНТА ##############
	public static function postAddGenerate(Request $request) {

		// простейшие проверки
		$template = TemplaterTemplates::find($request->id);
		if (!$template) {
			return redirect('/admin/templater/add');
		}

		// получение полей этого документа
		$fields = TemplaterFields::where('template_id', $request->id)->get();

		// получение всех боксов для документа
		$boxes = TemplaterBoxes::where('template_id', $request->id)->orderBy("sort", 'asc')->get();

		// генерация документа и запись в базу
		$documentId = TemplaterGenerate::generateNewdoc($template, $fields, $boxes, $request->id, $request->all() );

		// вывод для API
		if (($request->route()->getAction())['middleware'][0] == "api") {
			Helpers::ApiOutput($documentId, "id" );

		// вывод для веб-админки
		} else {
			return redirect('/admin/templater/list/card/'.$documentId);
		}
	}






	//############## АРХИВ ДОКУМЕНТОВ, НО НЕ ВСЕХ А СОЗДАННЫХ НА БАЗЕ ШАБЛОНОВ ##############
	public function getList(Request $request) {

		// категории документов для верхних вкладок
		$categories = DocumenterCategory::orderBy('sort', 'asc')->get();
		$category = DocumenterCategory::find($request->category_id);

		// получение документов
		$documents = $category->FindDocsFromTemplates;

		// финальная передача данных
		$data = [
			'categories' => $categories,
			'categoryId' => $category->id ?? 0,
			'documents' => $documents,
			];
		return view('admin.templater.list', $data);
		}




	//############## ПОКАЗ КАРТОЧКИ ДОКУМЕНТА  ##############
	public function getCard(Request $request) {
		$user = Auth::user();

		// простейшие проверки
		$document = Documenter::find($request->id);
		if (!$document) {
			return redirect('/admin/templater/list');
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
		return view('admin.templater.list_card', $data);
	}







}
