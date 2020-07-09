<?PHP
/*

Запус данного файла из крона:
/opt/php73/bin/php /var/www/getlaw/data/www/getlaw.ru/artisan currency:update

*/

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class UpdateCurrency extends Command {

	//The name and signature of the console command.
	protected $signature = 'currency:update';

	//The console command description.
	protected $description = 'Checks currency update';

	public function __construct() {
		parent::__construct();
	}




	public function handle() {

		$date = date("d/m/Y");
		$output = static::getValKurs($date);
		if ($output) {
			$fileNameToStore = "cbrf/" . date('Y.d.m') . '.php';
			Storage::put($fileNameToStore, $output);

			$fileNameToStore = 'cbrf/today.php';
			Storage::put($fileNameToStore, $output);
		}

	}




	public static function getValKurs(){
		$link = "http://www.cbr.ru/scripts/XML_daily.asp?date_req=" . date("d/m/Y");
		$fd = @fopen($link, "r");
		$content="";
		if($fd){
			while(!@feof ($fd)) $content .= @fgets($fd, 4096);
			}
		else return;
		@fclose ($fd);
	
		$pattern = "#<Valute ID=\"([^\"]+)[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>([^<]+)#i";
		preg_match_all($pattern, $content, $out, PREG_SET_ORDER);
	
		if (!empty($out)) {
			$count = -1;
	
			$mx_converter_output = "<?PHP\n return [\n";
	
			foreach($out as $cur){
				if ($cur[2] != "960") {

					$mx_converter_output .= "	'".$cur[3]."' => [\n";
						$mx_converter_output .= "		'NumCode' => ".$cur[2].",\n";
						$mx_converter_output .= "		'Nominal' => ".$cur[4].",\n";
						$mx_converter_output .= "		'Name' => '". preg_replace("/ Соединенного королевства/i", "", iconv("windows-1251", "UTF-8", $cur[5])) ."',\n";
						$mx_converter_output .= "		'Value' => ". preg_replace("/\,/", ".", $cur[6]) . ",\n";
					$mx_converter_output .= "	],\n";

					$count++;
					}
				}
	
			$mx_converter_output .= "];\n";
			}
	
		if (!empty($mx_converter_output)) {return $mx_converter_output;}
		else {return false;}
	}


/*
	public static function getValKurs(){
		$link = "http://www.cbr.ru/scripts/XML_daily.asp?date_req=" . date("d/m/Y");
		$fd = @fopen($link, "r");
		$content="";
		if($fd){
			while(!@feof ($fd)) $content .= @fgets($fd, 4096);
			}
		else return;
		@fclose ($fd);
	
		$pattern = "#<Valute ID=\"([^\"]+)[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>([^<]+)[^>]+>[^>]+>([^<]+)#i";
		preg_match_all($pattern, $content, $out, PREG_SET_ORDER);
	
		if (!empty($out)) {
			$count = -1;
	
			$mx_converter_output = '$currency_date = "' . date('d.m.Y') . '";' . "\n";
			$mx_converter_output .= '$currency_date_small = "' . date('d.m.y') . '";' . "\n\n";
	
			$mx_converter_output .= '$currency = Array();' 	. "\n\n";
	
			foreach($out as $cur){
				if ($cur[2] != "960") {
					$mx_converter_output .= '$currency["' . $cur[3] . '"]["NumCode"] = "' 	. $cur[2] . '";' . "\n";
					$mx_converter_output .= '$currency["' . $cur[3] . '"]["Nominal"] = "' 	. $cur[4] . '";' . "\n";
					$mx_converter_output .= '$currency["' . $cur[3] . '"]["Name"] = "' 		. preg_replace("/ Соединенного королевства/i", "", iconv("windows-1251", "UTF-8", $cur[5])) . '";' . "\n";
					$mx_converter_output .= '$currency["' . $cur[3] . '"]["Value"] = "' 		. preg_replace("/\,/", ".", $cur[6]) . '";' . "\n\n";
					$count++;
					}
				}
	
			$mx_converter_output .= '$currency_total = ' . $count . ';' . "\n";
			}
	
		if (!empty($mx_converter_output)) {return "<?PHP\n". $mx_converter_output;}
		else {return false;}
	}
*/


}