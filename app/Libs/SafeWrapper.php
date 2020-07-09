<?php

    namespace App\Libs;

    class SafeWrapper {
        protected $obj;

        public function __construct($obj) {
            $this->obj = $obj;
        }

        public function __get($key) {
            return $this->obj->$key ?? false;
        }
    }