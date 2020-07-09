<?php

    namespace App\Libs;

    use App\Models\Document;
    use Illuminate\Support\Facades\App;
    use Illuminate\Support\Facades\View;
    use App\Models\DJEM2\DJEMXml;

    class Helpers {
        public static $months = ['', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
        public static $monthsNominative = ['', 'январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'];
        public static $monthsNominativeShort = ['', 'янв', 'фев', 'март', 'апр', 'май', 'июнь', 'июль', 'авг', 'сен', 'окт', 'ноя', 'дек'];
        public static $weekdays = ['', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница ', 'суббота', 'воскресенье'];
        public static $weekdayShort = ['', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб', 'вс'];
        public static $monthsEng = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];





	// функция для вывода результатов сгенерированных данных для API в зависимости от формата который требуется
	public static function ApiOutput($params, $format = false) {
		$makeHidden = Array('autosum1','autosum2','sort','special','size','created','deleted');
		if ($format == "id") {
			print_r(Array(["id" => (string)$params]));
		} else if ($format == "serialize") {
			echo serialize($params->makeHidden($makeHidden)->toArray());
		} else if ($format == "array") {
			print_r($params->makeHidden($makeHidden)->toArray());
		} else if ($format == "json") {
			echo $params->makeHidden($makeHidden)->toJson();
		} else {
			echo false;
		}
		exit;
	}





        public static function Date($date, $withYear = false) {
            if (is_numeric($date)) {
                list (,,,$day, $month, $year) = localtime($date);
		}
		elseif (preg_match("/\d{2}\.\d{2}\.\d{4}/", $date)) {
                list ($day, $month, $year) = preg_split('#[^0-9]+#', $date);
            } else {
                list ($year, $month, $day) = preg_split('#[^0-9]+#', $date);
            }

            if ($withYear) {
//                return sprintf('%d %s %s', $day, static::$months[+$month], $year > 1900 ? $year : $year + 1900);
                return sprintf('%d %s %s', $day, static::$months[+$month], $year > 1900 ? $year : $year + 1900);
            } else {
//                return sprintf('%d %s', $day, static::$months[+$month], $year > 1900 ? $year : $year + 1900);
                return sprintf('%d %s', $day, static::$months[+$month], $year > 1900 ? $year : $year + 1900);
            }
        }






        public static function DateTime($date, $withYear = false) {
            if (is_numeric($date)) {
                list ($hour, $minute, $second, $day, $month, $year) = localtime($date);
            } else {
                list ($year, $month, $day, $hour, $minute, $second) = preg_split('#[^0-9]+#', $date);
//                --$month;
            }

            if ($withYear) {
                return sprintf('%d %s %s, %02d:%02d', $day, static::$months[+$month], $year > 1900 ? $year : $year + 1900, $hour, $minute);
            } else {
                return sprintf('%d %s, %02d:%02d', $day, static::$months[+$month], $hour, $minute);
            }
        }






        public static function DateTemplate($date) {
            if (is_numeric($date)) {
                list (,,,$day, $month, $year) = localtime($date);
            }

            if (App::getLocale() === 'en') {
                return sprintf('<div class="date"><div class="day">%02d</div><div class="month">%s</div></div>',
                    $day, static::$monthsEng[+$month]);

            } else {
                return sprintf('<div class="date"><div class="day">%02d</div><div class="month">%s</div></div>',
                    $day, static::$months[+$month]);
            }
        }





        public static function DateStandard01($date) {
            if (is_numeric($date)) {
                list ($hour, $minute, $second, $day, $month, $year) = localtime($date);
            } else {
                list ($year, $month, $day, $hour, $minute, $second) = preg_split('#[^0-9]+#', $date);
            }

            return sprintf('%s.%s.%s', $day, $month, $year > 1900 ? $year : $year + 1900);
        }

        public static function DateStandard02($date) {
            if (is_numeric($date)) {
                list ($hour, $minute, $second, $day, $month, $year) = localtime($date);
            } else {
                list ($year, $month, $day, $hour, $minute, $second) = preg_split('#[^0-9]+#', $date);
            }

		$month = str_pad($month, 2, '0', STR_PAD_LEFT);
		$day = str_pad($day, 2, '0', STR_PAD_LEFT);
		$hour = str_pad($hour, 2, '0', STR_PAD_LEFT);
		$minute = str_pad($minute, 2, '0', STR_PAD_LEFT);
		$second = str_pad($second, 2, '0', STR_PAD_LEFT);

            return sprintf('%s-%s-%s', $year > 1900 ? $year : $year + 1900, $month, $day);
        }





        public static function ShortDate($date) {
            if (!$date) return '';

            return strftime('%d.%m.%Y', $date);
        }





        public static function TimestampFromDate($date) {
            if (!$date) return time();
            return strtotime( preg_replace("/(\d{2})\.(\d{2})\.(\d{4})/", "$3-$2-$1", $date) );
        }





        public static function MysqlFromDate($date) {
            if (!$date) return time();
            return preg_replace("/(\d{2})\.(\d{2})\.(\d{4})/", "$3-$2-$1", $date);
        }





        public static function SepDate($date) {
            list (,,,$day, $month, $year) = localtime($date);

            return '<div class="day">' . sprintf('%02d', $day) . '</div><div class="month">' . static:: $months[+$month] . '</div>';
        }





	// преобразование 
	public static function PhoneStandard01($phone) {
	  	if (!is_numeric($phone)) {return $phone;}
	  	if (strlen($phone) != 11) {return $phone;}
		return preg_replace("/(\d)(\d{3})(\d{3})(\d{2})(\d{2})/", "+$1 ($2) $3-$4-$5", $phone);
	}




        static function RenderVideo($xml) {
            $data = LinkFetcher::Fetch($xml->video);
            $html = '';

            if (!empty($data['html'])) {
                $html = $data['html'];

                if (preg_match('#(<iframe [^>]*width)=\"?([0-9%]+)\"?#', $html, $m)) {
                    if (is_numeric($m[2])) {
                        $width = $m[2];
                        $newWidth = 720;

                        if (preg_match('#(<iframe [^>]*height)=\"?([0-9]+)\"?#', $html, $m)) {
                            $newHeight = $m[2] * $newWidth / $width;
                            $html = preg_replace('#(<iframe [^>]*width)=\"?([0-9]+)\"?#', '$1="' . $newWidth . '"', $html);
                            $html = preg_replace('#(<iframe [^>]*height)=\"?([0-9]+)\"?#', '$1="' . $newHeight . '"', $html);

                        } else {
                            $html = preg_replace('#(<iframe [^>]*width)=\"?([0-9]+)\"?#', '$1="100%"', $html);
                        }
                    }
                }

                $html = '<div class="video">' . $html . '</div>';
                // $html = '<div class="embed" data-embed="' . htmlspecialchars($html) . '"></div>';
            }

            return $html;
        }





        public static function RenderSpecialCut($xml) {
            switch ($xml->block_type) {
                case 'how_stuff_works':
                    return View::make('includes.how_stuff_works', [])->render();
                case 'discount':
                    return View::make('includes.discount', [])->render();
            }

            return '';
        }





        public static function RenderButton($xml) {
            return View::make('includes.button', ['text' => $xml->text, 'url' => $xml->site])->render();
        }





        public static function ProcessText($str, $options = []) {
            $str = preg_replace('#<(p[^>]*|div)>(&nbsp;|\r|\n|\t| |<br>|<br/>|<br />)*</(p|div)>#si', '', $str);

            if (preg_match_all('#(<div [^>]*mediablock="(.+?)"[^>]*>)[ \r\t\n]*(<span|<dl).+?(</dl>|<\!-- \/media -->)[^<]*</div>#si', $str, $matches)) {

                for ($i=0; $i < count($matches[0]); ++$i) {
                    $html = '';
                    $mediaBlockId = $matches[2][$i];

                    if (preg_match('#xml=[\r\n\t]*"(.+?)"#si', $matches[1][$i], $xmlMatches)) {
                        $data = base64_decode($xmlMatches[1]);
                        $xml = new DJEMXml($data); // о господи, количество врапперов! Помогите!

                        if ($mediaBlockId == 790) { // iframe
                            $html = static::RenderIframe($xml);

                        } else if ($mediaBlockId == 204) { // Специальный врез
                            $html = static::RenderSpecialCut($xml);

                        } else if ($mediaBlockId == 205) { // Видео
                            $html = static::RenderVideo($xml);

                        } else if ($mediaBlockId == 194) { // Кнопкуэ
                            $html = static::RenderButton($xml);

                        }
                    }

                    $str = str_replace($matches[0][$i], $html, $str);
                }
            }

            return $str;
        }






        public static function FileSize($size, $forceUnits = false) {
            if ($size > 1024 * 1024 || $forceUnits === 'mb') {
                if ($size > 1024 * 1024 * 10) {
                    return sprintf('%d MB', $size / 1024 / 1024);

                } else {
                    return sprintf('%.1f MB', $size / 1024 / 1024);
                }

            } else if ($size > 1024) {
                if ($size > 1024 * 10) {
                    return sprintf('%d kB', $size / 1024);

                } else {
                    return sprintf('%.1f kB', $size / 1024);
                }

            } else {
                return $size . ' B';
            }
        }





	static function FioShort($fio) {
		if (preg_match("/^\S+\s\S+\s\S+$/", $fio)) {
			$fio = preg_replace("/^(\S++)\s++(\S)\S++\s++(\S)\S++$/u", "$1 $2.$3.", $fio);
		}
	return $fio;
	}




        static function VerbalDigit($sum, $one, $four, $many, $includeNumber = true, $separateDigits = false) {
            $checkSum = $sum % 100;
            $prefix = $includeNumber ? ($separateDigits ? static::SeparatedSum($sum) : $sum) . ' ' : ''; // FIXME

            if ($checkSum <= 10 || $checkSum >= 20) {
                switch ($checkSum % 10) {
                    case 1:
                        return $prefix . $one;
                        break;

                    case 2:	case 3: case 4:
                    return $prefix . $four;
                }
            }

            return $prefix . $many;
        }





/**
 * Склоняем словоформу
 * @ author runcore
 */
static function VerbalDigitText($num, $rubles = false) {

	$nul='ноль';
	$ten=array(
		array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
		array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
	);
	$a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
	$tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
	$hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
	$unit=array( // Units
		array('копейка' ,'копейки' ,'копеек',	 1),
		array('рубль'   ,'рубля'   ,'рублей'    ,0),
		array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
		array('миллион' ,'миллиона','миллионов' ,0),
		array('миллиард','милиарда','миллиардов',0),
	);
	//
	list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
	$out = array();
	if (intval($rub)>0) {
		foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
			if (!intval($v)) continue;
			$uk = sizeof($unit)-$uk-1; // unit key
			$gender = $unit[$uk][3];
			list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
			// mega-logic
			$out[] = $hundred[$i1]; # 1xx-9xx
			if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
			else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
			// units without rub & kop
			if ($uk>1) $out[]= Helpers::Morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
		} //foreach
	}
	else $out[] = $nul;
	if ($rubles) {
		$out[] = Helpers::Morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
		$out[] = $kop.' '.Helpers::Morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
	}
	return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
}
static function Morph($n, $f1, $f2, $f5) {
	$n = abs(intval($n)) % 100;
	if ($n>10 && $n<20) return $f5;
	$n = $n % 10;
	if ($n>1 && $n<5) return $f2;
	if ($n==1) return $f1;
	return $f5;
}








        static function Video($url) {
            $data = LinkFetcher::Fetch($url);
            $html = '';

            if (!empty($data['html'])) {
                $html = $data['html'];

                if (preg_match('#(<iframe [^>]*width)=\"?([0-9%]+)\"?#', $html, $m)) {
                    if (is_numeric($m[2])) {
                        $width = $m[2];
                        $newWidth = 720;

                        if (preg_match('#(<iframe [^>]*height)=\"?([0-9]+)\"?#', $html, $m)) {
                            $newHeight = $m[2] * $newWidth / $width;
                            $html = preg_replace('#(<iframe [^>]*width)=\"?([0-9]+)\"?#', '$1="' . $newWidth . '"', $html);
                            $html = preg_replace('#(<iframe [^>]*height)=\"?([0-9]+)\"?#', '$1="' . $newHeight . '"', $html);

                        } else {
                            $html = preg_replace('#(<iframe [^>]*width)=\"?([0-9]+)\"?#', '$1="100%"', $html);
                        }
                    }
                }

                $html = '<div class="video">' . $html . '</div>';
            }

            return $html;
        }






        public static function Now($timeStamp = false, $withTime = true) {
            if ($timeStamp == false) {
                $timeStamp = time();
            } else if (!is_numeric($timeStamp)) {
                $timeStamp = strtotime($timeStamp);
            }

            if ($withTime) {
                return strftime('%Y-%m-%d %H:%M:%S', $timeStamp);
            } else {
                return strftime('%Y-%m-%d', $timeStamp);
            }
        }


	// форматирование номера кредитной карты в виде 0000 0000 0000 0000
	public static function cсardNumberVusial($digit) {
		if (strlen($digit) > 13) {
			return preg_replace("/(\d+)(\d{4})(\d{4})(\d{4})$/", "$1 $2 $3 $4", $digit);
		}
		else if (strlen($digit) > 9) {
			return preg_replace("/(\d+)(\d{4})(\d{4})$/", "$1 $2 $3", $digit);
		}
		else if (strlen($digit) > 5) {
			return preg_replace("/(\d+)(\d{4})$/", "$1 $2", $digit);
		}
		else {
			return $digit;
		}
	}



	// форматирование баркода EAN-13 с правильным подсчетом контрольной суммы
	public static function barcodeNumber($digits) {
		$digits =(string)$digits;
		$even_sum = $digits{1} + $digits{3} + $digits{5} + $digits{7} + $digits{9} + $digits{11};
		$even_sum_three = $even_sum * 3;
		$odd_sum = $digits{0} + $digits{2} + $digits{4} + $digits{6} + $digits{8} + $digits{10};
		$total_sum = $even_sum_three + $odd_sum;
		$next_ten = (ceil($total_sum/10))*10;
		$check_digit = $next_ten - $total_sum;
		return $digits . $check_digit;
	}








    }