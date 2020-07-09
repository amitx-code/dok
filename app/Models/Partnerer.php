<?php

namespace App\Models;

class Partnerer extends CachedModel {
	protected $table = 'partnerer_list';
	public $timestamps = false;
	protected $guarded = array('id');

}