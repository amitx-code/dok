<?php 

namespace App\Models;

use App\Exceptions\PurchaserException;
use App\Libs\Helpers;
use PhpOffice\PhpWord\Reader\Word2007;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

use \ConvertApi\ConvertApi;

class PurchaserDownload extends CachedModel {
	protected $table = 'purchaser_list';
	public $timestamps = false;
	protected $guarded = array('id');







	public static function downloadDocument($document, $action) {
		$user = Auth::user();

		// проверки
		$filePatch = $_SERVER['DOCUMENT_ROOT'].'/storage/purchaser/' . $document->filename;
       	if (!file_exists($filePatch)) {
			throw new PurchaserException('Not found file '.$document->filename, PurchaserException::GENERAL_ERROR);
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

/* 
			// Попытка генерации штатным способам, но получается дебильный WebPDF, поэтому не подходит
			// Пока оставлю это тут, вдруг пригодится
			$domPdfPath = realpath($_SERVER['DOCUMENT_ROOT'].'/storage/purchaser/' );
			\PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
			\PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
			$phpWord = \PhpOffice\PhpWord\IOFactory::load($filePatch); 
			$xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord , 'PDF');
			$xmlWriter->save($filePatchPdf);
*/

			// Если PDF ранее не генерировался
       		if (!file_exists($filePatchPdf)) {

				// Использование сервиса https://www.convertapi.com (платный)
				ConvertApi::setApiSecret( Config('convertapi.ApiSecret') );
				$result = ConvertApi::convert(
					'pdf', [
						'File' => $filePatch,
						'PdfResolution' => Config('convertapi.PdfResolution') ,
					], 'docx'
				);
				$result->saveFiles($filePatchPdf);

			} 

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
			
		return false;
	}






}