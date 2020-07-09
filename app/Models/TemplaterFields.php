<?php

    namespace App\Models;

    class TemplaterFields extends CachedModel {
        protected $table = 'templater_fields';
        public $timestamps = false;
        protected $guarded = array('id');


    }