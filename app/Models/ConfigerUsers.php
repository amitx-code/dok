<?php

namespace App\Models;

class ConfigerUsers extends CachedModel {
        protected $table = 'users';
        public $timestamps = false;
        protected $guarded = array('id');



        public function SetDefaultFields($user) {
            parent::SetDefaultFields($user);
            $this->sort = time();
        }



}