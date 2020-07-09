<?php

    namespace App\Libs;

    use App\Models\Billing;
    use App\Models\PhoneCall;

    class Callback {
        /**
         * @var Sipnet
         */
        protected $sipnet = null;
        protected $priceCoeff;

        public function __construct() {
            $this->sipnet = new Sipnet();
            $this->priceCoeff = 3;
        }



        public static function validatePhone($phone, &$error = '') {
            $phone = preg_replace('#[^+0-9]#', '', $phone);

            if (strlen($phone) && $phone{0} == '8') {
                $phone = '+7' . substr($phone, 1);
            }

            if (strpos($phone, '495') === 0 || strpos($phone, '499') === 0) {
                $phone = '+7' . $phone;
            }

            if (strlen($phone) && $phone{0} != '+') {
                $error = 'Пожалуйста введите телефон с кодами страны и города.<BR>Например: +7 495 000-00-00 или +7 912 0000000 или +380 44 0000000 и т.п.';
                return false;
            }

            if (strlen(preg_replace('#[^0-9]#', '', $phone)) < 11) {
                $error = 'Номер слишком короткий';
                return false;
            }

            return $phone;
        }


        public static function makeACall($phone, $phoneOur, $costLimit = 0) {
            $conferenceId = static::createConference($phone, $phoneOur, $costLimit);

            return $conferenceId;
        }


        public static function createConference($phone, $phoneOur, $costLimit) {
            $sipnet = new Sipnet();
            $conferenceId = $sipnet->requestApi('genCall', [
                'SrcPhone' => static::validatePhone($phoneOur),
                'DstPhone' => static::validatePhone($phone),
            ])->id;

            return $conferenceId;
        }


        public function updateActiveConferences() {
            $phoneCalls = PhoneCall::where('status', PhoneCall::STATUS_STARTED);

            foreach ($phoneCalls->get() as $phoneCall) {
                try {
                    $data = $this->sipnet->requestApi('calls2', ['ID' => $phoneCall->conference_id]);
                    if (empty($data->calls2[0])) {
                        if (strtotime($phoneCall->created) < (time() - 86400)) {
                            $phoneCall->status = PhoneCall::STATUS_FAILED;
                            $phoneCall->save();
                        }

                        continue;
                    }

                    $callData = $data->calls2[0];
                    if (empty($callData->cidsrc)) {
                        // Не закончился или не работает
                        if (strtotime($phoneCall->created) < (time() - 86400)) {
                            print "Gotta cancel the call $phoneCall->id\n";
                        }

                        continue;
                    }

                    $callParts = [];
                    if (!empty($callData->durationsrc)) {
                        $this->addCallData($callParts, $phoneCall->phone_our, $callData->durationsrc);
                    }

                    if (!empty($callData->durationdst)) {
                        $this->addCallData($callParts, $phoneCall->phone, $callData->durationdst);
                    }

                    list ($callCost, $callDuration, $callMaxDuration) = array_reduce($callParts, function($carry, $item) {
                        return [$carry[0] + $item->cost, $carry[1] + $item->duration, max($carry[2], $item->duration)];
                    }, [0, 0, 0]);

                    $details = $phoneCall->details;
                    $details->calls = $callParts;

                    $phoneCall->details = $details;
                    $phoneCall->sum = $callCost;
                    $phoneCall->duration = $callDuration;
                    $phoneCall->status = PhoneCall::STATUS_COMPLETED;
                    $phoneCall->save();

                    print "Call $phoneCall->id, price: $callCost, duration: $callDuration\n";
                    Billing::getBalance($phoneCall->user_id, true);

                } catch (\Exception $e) {
                    print "Call failed\n";
                }

            }
        }


        public function addCallData(&$details, $phone, $duration) {
            $price = $this->sipnet->getPriceForPhone($phone);
            $price *= $this->priceCoeff;
            $cost = $price * ($duration / 60);
            $details[] = (object) ['phone' => $phone, 'cost' => $cost, 'duration' => $duration];
        }

    }