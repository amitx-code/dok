<?php

    namespace App\Models;

    class TemplaterBoxes extends CachedModel {
        protected $table = 'templater_boxes';
        public $timestamps = false;
        protected $guarded = array('id');


    }