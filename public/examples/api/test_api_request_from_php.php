<?PHP





/*############### ПРИМЕР ВЫЗОВА API ИЗ PHP БЕЗ ДОПОЛНИТЕЛЬНЫХ ПАКЕТОВ #####################*/

$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjMxMTA3OTJmZDlmZjJmZDAyMTk0NDcyMzgzOWVmNjAwMTUzYWZkMjEzMWU4ZGI5N2NmZGYwMmMzMmVmMDY2MDY3NmI4MzQ3YjIxMzVkYjA1In0.eyJhdWQiOiI2NCIsImp0aSI6IjMxMTA3OTJmZDlmZjJmZDAyMTk0NDcyMzgzOWVmNjAwMTUzYWZkMjEzMWU4ZGI5N2NmZGYwMmMzMmVmMDY2MDY3NmI4MzQ3YjIxMzVkYjA1IiwiaWF0IjoxNTQ1ODUwNTAwLCJuYmYiOjE1NDU4NTA1MDAsImV4cCI6MTU3NzM4NjUwMCwic3ViIjoiMCIsInNjb3BlcyI6W119.B_MZGJtRc29MJRVVMgN7YeZ1Fyll_7fhGiit1j-YK66ivp46nlqGYgiUgX6uFc6Oyzpmr8bp8Zg_n1W_FAbJkE89ta-vbjrqPAEcWrhRtCeGNdsGIOko62nX5lnn-aXrfvyuWyNOzN7GTsz2TBIFDN7gpavemZG4y-Kbe3didhzkqPvOUqTbAfW_TblheMOgfP-iCOs3fXEzDRPixJBkd1_aKqx0hTVPrAz0kL6Jg-NPJHG5Rwi8ucwlyVDiyXss8u1CV7LMWWea9OkOrU7-WEKehCJZYe9hRimmHOikVkdoaAKmPKTNQjNQrwFlFahbCBYQjMN4wEq8stQqRL9lFdth4PFOM-TaFIveCKfQTBDyZ0UaKFBqwAlOkjXJIRSxFjA-zL9oZIucFtGR0RAs1s7UYom07xmjeKIw9mHeFM02QbQZMpVh1zU3qj3QJUY_oiTadWG7brdNrMg8LqOXa8v2vQceOWd3e79TlR0JuLxHsQwk_N7T-onf3jjRkVDGzcH3UrP5wukGWmsnzXLAfi0zsthVcsqXTVP4vipn1m8GiVK9LNhgL9MF7kth9rGJM42h-RxwTSVpQwtw7OqrU50HoXlxLzOl5pzpG0vX5GC-7cjYGYR1Y3TU_nY1iavzUAVGutJMVG4jPv6tgFLuTUm_kM55Aq5gNZrRk4SgeUU";

$params = Array(
	'api_format' => 'array', // json, array или serialize
	'api_essence' => '', // указание дополнительной сущности которую нужно получить (если это требуется)
);

// URL для получения списка всех шаблонов
$url = "https://" . $_SERVER['SERVER_NAME'] . "/api/templater/add";

// URL для получения списка всех полей шаблона с нужным ID
// возможный дополнительный параметр для получения списка категорий шаблонов
// 'api_essence' => 'categories'
// $url = "https://" . $_SERVER['SERVER_NAME'] . "/api/templater/add/5/start";

// URL для генерации нового документа не основе шаблона с нужным ID
// $url = "https://" . $_SERVER['SERVER_NAME'] . "/api/templater/add/5/generate";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
curl_exec($ch);
curl_close($ch);
