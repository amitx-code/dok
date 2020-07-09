<?php

namespace App\Models;

class oAuthClient extends CachedModel {
	protected $table = 'oauth_clients';
	public $timestamps = false;
	protected $guarded = array('id');
}
