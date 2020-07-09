<?php

    namespace App\Rules;

    use Illuminate\Contracts\Validation\Rule;

    class Phone implements Rule
    {
        protected $errorMessage = 'validation.phone';

        /**
         * Create a new rule instance.
         *
         * @return void
         */
        public function __construct()
        {
            //
        }

        /**
         * Determine if the validation rule passes.
         *
         * @param  string  $attribute
         * @param  mixed  $value
         * @return bool
         */
        public function passes($attribute, $value)
        {
            $phone = preg_replace('#[^+0-9]#', '', $value);

            if (strlen($phone) && $phone{0} == '8') {
                $phone = '+7' . substr($phone, 1);
            }

            if (strpos($phone, '495') === 0 || strpos($phone, '499') === 0) {
                $phone = '+7' . $phone;
            }

            if (strlen($phone) && $phone{0} != '+') {
                $this->errorMessage = 'validation.phone:no_code';
                return false;
            }

            if (strlen(preg_replace('#[^0-9]#', '', $phone)) < 11) {
                $this->errorMessage = 'validation.phone:short';
                return false;
            }

            return true;
        }

        /**
         * Get the validation error message.
         *
         * @return string
         */
        public function message()
        {
            return trans($this->errorMessage);
        }
    }