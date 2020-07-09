<?php 

namespace App\Models;

use App\Exceptions\TemplaterException;
use App\Libs\Helpers;
use PhpOffice\PhpWord\Reader\Word2007;
use Illuminate\Support\Facades\Auth;


class TemplaterGenerate extends CachedModel {
	protected $table = 'documenter_list';
	public $timestamps = false;
	protected $guarded = array('id');









	public static function generateNewdoc($template, $fields, $boxes, $docId, $params) {

		$user = Auth::user();
		$its_docnumber = "";
		$its_docdate = "";
		$its_docclient = "";
		$its_docsumm = 0;
		$its_docemail = "";
		$autosum1 = 0; // автосумма 1 (если она будет рассчитываться)
		$autosum2 = 0; // автосумма 1 (если она будет рассчитываться)
		$cloneCount = 1; // количество циклов которые нужно клонировать в строчке (если она вообще будет в документе)
		$paramsToDatabase = Array(); // это будет массив, который будет писаться как serialize в БД и в сессию

		// чтение шаблона документа * толковая статья http://docs.mirocow.com/doku.php?id=php:docx_doc 
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$fileTemplate = $_SERVER['DOCUMENT_ROOT'].'/../resources/doc_templates/' . $template->filename;
            if (!file_exists($fileTemplate)) {
			throw new TemplaterException('Not found template '.$fileTemplate, TemplaterException::GENERAL_ERROR);
            }
		$tpl = $phpWord->loadTemplate($fileTemplate);

		// клонирование строк если есть которые нужно клонировать		
		foreach ($boxes as $box) {
			if ($box->view == "rows") {
				if (
					isset( $params["CLONE_" . $box->id . "_TOTAL"] ) && 
					isset( $params["CLONE_" . $box->id . "_NAME"] ) 
				) {
					$tpl->cloneRow( $params["CLONE_" . $box->id . "_NAME"] , $params["CLONE_" . $box->id . "_TOTAL"] );

					$cloneCount = $params["CLONE_" . $box->id . "_TOTAL"];
					session( [ "CLONE_" . $box->id . "_TOTAL" => $cloneCount ] );
					$paramsToDatabase[ "CLONE_" . $box->id . "_TOTAL" ] = $cloneCount;

				}
			}
		}


		// #########################################################################################
		// перебор всех полей документа, и сравнение их с переданными из формы полями
		foreach ($fields as $field) {
			for ($i = 0; $i <= $cloneCount+1; $i++) { // плюс по клонированным строкам если надо
				
				if ($i==0) {$dop="";}
				else {$dop = "#".$i;}

				// если в переданном есть такое поле  и если оно не файл для закачки картинки
				if ( isset( $params[$field->name . $dop] ) && !preg_match("/FILE/", $field->name) ) {
	
					$nameValue = $field->name . $dop;
					$paramValue = $params[$nameValue];

					// если данное поле является массивом (чекбокс, радио), то переводим его в стринг
					if (is_array($paramValue)) {
						$paramValue = implode(", ", $paramValue);
					}

					// если данное поле является цифрой формата стринга как 0 000 000 000,00
					elseif (preg_match("/\d+\,\d{2}$/", $paramValue)) {
						$paramValue = number_format( floatval( preg_replace("/\s/", "", preg_replace("/\,/", ".", $paramValue))) , 2, '.', '');
					}

					// занесение в массив всех значений чтобы потом этот массив сохранить в базе и сессии
					$paramsToDatabase[$nameValue] = $paramValue; 

					// если данное поле участвует в формировании опиания документов
					if ($field->special) {
						$temp = $field->special;
						if ($$temp != "") {$$temp .= " ";}
						$$temp .= $paramValue;
					}
	
					// внесение в док-файл всех трансформированных данных
					if ($field->transformation == "date_to_text") {
						$tpl->setValue( $nameValue, Helpers::Date( $paramValue, true ) );
					}
					elseif ($field->transformation == "digit_to_text") {
						$tpl->setValue( $nameValue . "(TEXT)", Helpers::VerbalDigitText( $paramValue ) );
						$tpl->setValue( $nameValue , $paramValue );
					}
					elseif ($field->transformation == "digit_to_textrubles") {
						$tpl->setValue( $nameValue . "(TEXT)", Helpers::VerbalDigitText( $paramValue , true ) );
						$tpl->setValue( $nameValue , number_format( floatval($paramValue) , 2, ',', ' ' ) );
						$nds = (floatval( preg_replace("/\s/", "", preg_replace("/\,/", ".", $paramValue ))) /120) * 20;
						$tpl->setValue( $nameValue . "(NDS)", number_format($nds, 2, ',', ' ' ) );
					}
					elseif (preg_match("/_FIO/", $nameValue) || preg_match("/_NAME/", $nameValue)) {
						$tpl->setValue( $nameValue , $paramValue );
						$tpl->setValue( $nameValue . "(SHORT)", Helpers::FioShort( $paramValue ) );
					}
					// внесение в док-файл всех остальных данных
					else {
						$tpl->setValue( $nameValue , $paramValue );
					}

					// если данное поле должно участвовать в автосумме
					if ($field->autosum1) {
						$autosum1 = $autosum1 + floatval($paramValue);
					}
					if ($field->autosum2) {
						$autosum2 = $autosum2+ floatval($paramValue);
					}

					// Хак-заплатка - для счетов
					// чтобы автоматически делилась бы сумма каждой строчки на кол-во в этой строчке
					// работать будет отлично, но универсальность снижается - придётся использовать строго
					// заданные названия полей, и нельзя придумывать что-то своё.
					// Ничего умнее не придумал. Может быть потом как-нибудь.
					if (
						$nameValue == "SERVICES_SUMM".$dop &&
						isset($params["SERVICES_SUMM".$dop]) &&
						isset($params["SERVICES_QNT".$dop])
					) {
						$param1 = floatval( preg_replace("/\s/", "", preg_replace("/\,/", ".", $params["SERVICES_SUMM".$dop] )));
						$param2 = floatval( preg_replace("/\s/", "", preg_replace("/\,/", ".", $params["SERVICES_QNT".$dop] )));
						$price = $param1 / $param2 ;
						$tpl->setValue( 'SERVICES_PRICE'.$dop, number_format( $price , 2, ',', ' ' ) );
					}

				}


				// если переданное поле пустое и его нет, но ведь в шаблоне ворда его нужно обнулить
				else if ( !isset( $params[$field->name . $dop] ) && !preg_match("/FILE/", $field->name) ) {

					$nameValue = $field->name . $dop;
					$paramValue = "";

					// занесение в массив всех значений чтобы потом этот массив сохранить в базе и сессии
					$paramsToDatabase[$nameValue] = $paramValue; 

					// внесение в док-файл всех данных
					$tpl->setValue( $nameValue , $paramValue );

				}


			}
		}
		// #########################################################################################



		// #########################################################################################
		// перебор всех загруженных файлов если они есть
		$allowedFilesGrafhics = Array('application/pdf', 'image/gif', 'image/jpeg', 'image/tiff', 'image/png');
		$allowedFilesWordtext = Array('application/msword', 'application/rtf', 'application/x-rtf', 'text/richtext', 'text/rtf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
		for ($f=1; $f<=3; $f++) { // три поля для аплоада файлов, увеличить если их будет больше
			if (!empty($params[ "FILE".$f ]) ) {

				// чтение файла
				$file = $params[ "FILE".$f ];
				$fileType = $_FILES[ "FILE".$f ]['type'];

				// проверки на нужные рассширения ГРАФИКИ и допускаем только те которые разрешены
				if (in_array($fileType, $allowedFilesGrafhics)) {

					$im = new \Imagick();
					$im->readImage($file);

					// получение всех страниц загруженного PDF, а если нет, то берётся стр изображения картинки
					if ($fileType == "application/pdf") {$pages=$im->getIteratorIndex();} else {$pages=0;}
					for ($x = 0; $x <= $pages; $x++) {

						// подмена текущего поля чтобы можно было вставлять много страниц
						$tpl->setValue( 'FILES', '${FILE_NOW_HERE}${FILES}' ); 

						// чтение страницы и преобразование её в картинку
						$im->readImage($file . '[' . $x . ']');
						$im->setResolution(150, 150);
						$im->setImageFormat('jpg');
						$im->writeImage(sys_get_temp_dir() . '/p-'.$x.'.jpg');

						// вставка картинки в PHPWord
						// улучшение процессора и команда "setImageValue" сдалано дополнением описанным тут: 
						// https://github.com/PHPOffice/PHPWord/pull/1170
						$tpl->setImageValue('FILE_NOW_HERE', 
							array(
								'path' => sys_get_temp_dir() . '/p-'.$x.'.jpg', 
								'width' => 707, 
								'height' => 1000, 
								'ratio' => true
							)
						);
					}

					$im->clear();
				} 

				// проверки на нужные рассширения ТЕКСТОВ и допускаем только те которые разрешены
				elseif (in_array($fileType, $allowedFilesWordtext)) {

/*
 тут нихрена не получилось - так как нет нормального способа вставить ворд в другой ворд :(
нужно все переделывать на сначала конвертацию в PDF, а потом уже в картинку

					// подмена текущего поля чтобы можно было вставлять много страниц
					$tpl->setValue( 'FILES', '${FILE_NOW_HERE}${FILES}' ); 


// чтение загруженного документа
//$phpWordImport = new \PhpOffice\PhpWord\PhpWord();
//$docImport = $phpWordImport->loadTemplate($file);
//print_r($docImport); exit;

$text = '';
$phpWordImport = \PhpOffice\PhpWord\IOFactory::load($file);
$sections = $phpWordImport->getSections();
foreach ($sections as $section) {

print get_class($section)."<hr>";
$tpl->addObject($section);

	$elements = $section->getElements();
	foreach ($elements as $element) {

	  if (get_class($element) === 'PhpOffice\PhpWord\Element\TextRun') {
            $text .= $element->getText();
        } elseif (get_class($element) === 'PhpOffice\PhpWord\Section\TextBreak') {
            $text .= " \n";
        } 

	}
}
//print $text;
exit;

$tpl->setValue( 'FILES', $docImport );

$tpl->setValue( 'FILES', $docImport );

*/



				}


			}
		}
		$tpl->setValue( 'FILES', '' );
		// #########################################################################################


		// внесение полей автосумм
		$tpl->setValue( 'AUTOSUM1', number_format( $autosum1 , 2, ',', ' ' ) );
		$tpl->setValue( 'AUTOSUM1(TEXT)', Helpers::VerbalDigitText( $autosum1 , true ) );
		$tpl->setValue( 'AUTOSUM1(COUNT)', number_format($cloneCount, 0, ',', ' ' ) );
		$nds = (floatval( preg_replace("/\s/", "", preg_replace("/\,/", ".", $autosum1 ))) /120) * 20;
		$tpl->setValue( 'AUTOSUM1(NDS)', number_format($nds, 2, ',', ' ' ) );

		$tpl->setValue( 'AUTOSUM2', number_format( $autosum2 , 2, ',', ' ' ) );
		$tpl->setValue( 'AUTOSUM2(TEXT)', Helpers::VerbalDigitText( $autosum2 , true ) );
		$tpl->setValue( 'AUTOSUM2(COUNT)', number_format($cloneCount, 0, ',', ' ' ) );
		$nds = (floatval( preg_replace("/\s/", "", preg_replace("/\,/", ".", $autosum2 ))) /120) * 20;
		$tpl->setValue( 'AUTOSUM2(NDS)', number_format($nds, 2, ',', ' ' ) );

		// коррекция суммы документа, если она считается автоматически
		if ($its_docsumm == 0 && $autosum1 > 0) {
			$its_docsumm = $autosum1;
		}

		// запись в сессию всех последних полей введенных недавно и подготовка массива для записи в базу
		foreach ($paramsToDatabase as $key => $value) {
			session( [ $key => $value ] );
		}
		session( [ "fill" => "last" ] ); // детект того что была запись в сессию

		// запись итогового файла в базу данных
            $item = new static();
            $item->fill([
			'category_id' => $template->category_id, 
			'template_id' => $template->id, 
			'name' => $template->name, 
			'number' => $its_docnumber,
			'date' => Helpers::MysqlFromDate($its_docdate),
			'client' => $its_docclient,
			'email' => $its_docemail,
			'summ' => $its_docsumm,
			'array' => serialize($paramsToDatabase),
			'user_id' => $user->id,
			'created' => date('Y-m-d H:i:s'),
		]);
            if (!$item->save()) {
			throw new TemplaterException('Unable to add document, please contact to developer', 
				TemplaterException::GENERAL_ERROR);
            }

		// формирование имени файла на основании его значений
		$fileName = "";
		if (!empty($its_docdate)) {$fileName .= preg_replace("/(\d+)\.(\d+)\.(\d+)/", "$3.$2.$1", $its_docdate) . " ";}
		$fileName .= $template->name;
		if (!empty($its_docnumber)) {$fileName .= " ". $its_docnumber;}
		if (!empty($its_docclient)) {$fileName .= " ". $its_docclient;}
		$fileName .= " #D" . str_pad($item->id, 7, "0", STR_PAD_LEFT) . "#";
		$fileName .= ".docx";

		// запись итогового файла на диск
		$tempFile = $tpl->save();
		copy($tempFile, $_SERVER['DOCUMENT_ROOT'].'/storage/documenter/' . $fileName);
		unlink($tempFile);

		// перезаписываем имя файла чтобы в нем указать ID документа
            $item->fill([
			'filename' => $fileName,
		]);
		$item->save();

		// возвращаем ID сделанной в БД записи
		return $item->id;
	}













/*
	public static function downloadDocument($document, $action) {
		$user = Auth::user();

		// проверки
		$filePatch = $_SERVER['DOCUMENT_ROOT'].'/storage/documenter/' . $document->filename;
       	if (!file_exists($filePatch)) {
			throw new TemplaterException('Not found file '.$document->filename, TemplaterException::GENERAL_ERROR);
		}

		// подготовка заголовков и параметров для скачивания Word
		if ($action == "doc") {
			$headers = Array();
			$headers[] = 'Content-Description: File Transfer';
			$headers[] = 'Content-Type: application/msword';
			$headers[] = "Content-Disposition: attachment; filename='" . $document->filename. "'";
			$headers[] = 'Content-Transfer-Encoding: binary';
			$headers[] = 'Expires: 0';
			$headers[] = 'Cache-Control: must-revalidate';
			$headers[] = 'Pragma: public';
			$headers[] = 'Content-Length: ' . filesize($filePatch);
			return Array($filePatch, $document->filename, $headers);
			}

		// подготовка заголовков и параметров для скачивания PDF
		if ($action == "pdf") {

$filePatchPdf = preg_replace("/\.docx$/", ".pdf", $filePatch);

$domPdfPath = realpath($_SERVER['DOCUMENT_ROOT'].'/storage/documenter/' );
\PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
\PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');

$phpWord = \PhpOffice\PhpWord\IOFactory::load($filePatch); 
$xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord , 'PDF');
$xmlWriter->save($filePatchPdf);

			$headers = Array();
			$headers[] = 'Content-Description: File Transfer';
			$headers[] = 'Content-Type: application/pdf';
			$headers[] = "Content-Disposition: attachment; filename='" . preg_replace("/\.docx$/", ".pdf", $document->filename) . "'";
			$headers[] = 'Content-Transfer-Encoding: binary';
			$headers[] = 'Expires: 0';
			$headers[] = 'Cache-Control: must-revalidate';
			$headers[] = 'Pragma: public';
			$headers[] = 'Content-Length: ' . filesize($filePatchPdf);
			return Array($filePatchPdf, preg_replace("/\.docx$/", ".pdf", $document->filename), $headers);
			}

		// подготовка заголовков и параметров для отправки Емейл
		if ($action == "email") {
			$headers = Array();
			$headers[] = 'Content-Description: File Transfer';
			$headers[] = 'Content-Type: application/msword';
			$headers[] = "Content-Disposition: attachment; filename='" . $document->filename. "'";
			$headers[] = 'Content-Transfer-Encoding: binary';
			$headers[] = 'Expires: 0';
			$headers[] = 'Cache-Control: must-revalidate';
			$headers[] = 'Pragma: public';
			$headers[] = 'Content-Length: ' . filesize($filePatch);
			return Array($filePatch, $document->filename, $headers);
			}
			
		return false;
	}
*/





}