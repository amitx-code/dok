<?php

    namespace App\Libs;

    use Illuminate\Support\Facades\Config;

    class Sipnet {
        protected $config;

        public function __construct() {
            $this->config = (object) Config::get('sipnet');
        }

        public function getPriceForPhone($phone) {
            $data = $this->requestApi('getphoneprice', ['phone' => $phone, 'format' => 2]);
            return $data->minprice;
        }


        public function getPriceFor2Phones($phone1, $phone2, $time = 1) {
            $price1 = $this->getPriceForPhone($phone1);
            $price2 = $this->getPriceForPhone($phone2);

            return ($price1 + $price2) * $time;
        }


        public function requestApi($operation, $params = []) {
            $requestParams = array_merge([
                'operation' => $operation,
                'sipuid' => $this->config->uid,
                'format' => 2,
                'password' => $this->config->password,
            ], $params);

            $uri = $this->config->endpoint . '?' . http_build_query($requestParams);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $uri);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

            $result = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

            $f = fopen($_SERVER['DOCUMENT_ROOT'] . '/tmp/sipnet.log', 'a+');
            if ($f) {
                fwrite($f, "\n---------------------------\n");
                fwrite($f, "Status: $statusCode\n");
                fwrite($f, var_export($result, true));
                fclose($f);
            }

            if ($statusCode !== 200) {
                throw new \Exception(curl_error($ch), $statusCode);
            }

            $data = json_decode($result);
            if (empty($data)) {
                throw new \Exception('Unable to decode');
            }

            if ($data->Result !== true) {
                throw new \Exception('Result was not successful');
            }

            return $data;
        }
    }