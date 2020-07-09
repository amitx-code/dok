<?PHP





/*############### ПРИМЕР АВТОРИЗАЦИОННОЙ СТРАНИЦЫ САЙТА #####################*/
// используется для тестов oAut авторизации для API - чтобы запросить токен для этого же самого сайта как для самого себя

// URL для запроса авторизации
$url = 'https://' . $_SERVER['SERVER_NAME'] . '/oauth/token';

// параметры для авторизации
$params = Array(
            'client_id' => 69, // заменить на ID вашего сайта
            'client_secret' => 'ac30e63f846fce098ec7f8a9f21adb8d', // заменить на "секретный ключ" вашего сайта
            'redirect_uri' => 'https://getlaw24.ru/examples/api/test_callback_php.php', // заменить на адрес вашего внешнего сайта
            'grant_type' => 'authorization_code', // не изменять
            'code' => $_REQUEST['code'], // не изменять
);

// запуск авторизации
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
curl_exec($ch);
curl_close($ch);
