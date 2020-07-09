<?php

namespace App\Models;

class Purchaser extends CachedModel {
	protected $table = 'purchaser_list';
	public $timestamps = false;
	protected $guarded = array('id');

}