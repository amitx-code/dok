<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;

class PartnererTerritory extends CachedModel {
        protected $table = 'partnerer_territories';
        public $timestamps = false;
        protected $guarded = array('id');



        public function SetDefaultFields($user) {
            parent::SetDefaultFields($user);
            $this->sort = time();
        }




}