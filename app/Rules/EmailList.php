<?php

    namespace App\Rules;

    use Illuminate\Contracts\Validation\Rule;
    use Illuminate\Support\Facades\Validator;

    class EmailList implements Rule
    {
        protected $errorMessage = 'validation.email_list';

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
            $rules = ['email' => 'email'];

            $emails = preg_split('#[ \r\t\n]+#', $value);

            foreach ($emails as $email) {
                if (empty($email)) continue;

                $data = ['email' => $email];
                $validator = Validator::make($data, $rules);
                if ($validator->fails()) {
                    $this->errorMessage = 'validation.email_list:format';
                    return false;
                }
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