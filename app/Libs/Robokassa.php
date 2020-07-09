<?php

    namespace App\Libs;

    use App\Exceptions\BillingException;
    use Illuminate\Support\Facades\Config;

    class Robokassa {
        protected $config;

        public function __construct() {
            $this->config = (object) Config::get('robokassa');
        }





        public function createRoboPaymentLink($invoiceId, $sum, $description) {
            $options = array(
                'MrchLogin' => $this->config->login,
                'OutSum' => $sum,
                'InvId' => $invoiceId,
                'Desc' => $description,
                'IsTest' => $this->config->IsTest,
            );

          $signatureStr = $options['MrchLogin'] . ':' . $options['OutSum'] . ':' . $options['InvId'] . ':' . $this->config->password1;
            $signature = md5($signatureStr);

            $options['SignatureValue'] = $signature;

            $url = $this->config->endpoint . '?' . http_build_query($options);

            return $url;
        }





        public function checkResult($mode = 'result', $request) {
            $sum = $request->OutSum;
            $invoiceId = $request->InvId;

            $signature = strtoupper(md5($sum . ':' . $invoiceId . ':' . ($mode == 'result' ? $this->config->password2 : $this->config->password1)));

            if ($signature != strtoupper($request->SignatureValue)) {
                throw new BillingException('Bad signature', BillingException::BAD_SIGNATURE);
            }

            return (object) ['sum' => $sum, 'invoiceId' => $invoiceId];
        }

    }