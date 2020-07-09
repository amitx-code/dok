<?php

namespace App\Models;

class ConfigerUsersRoles extends CachedModel {
        protected $table = 'users_roles';
        public $timestamps = false;
        protected $guarded = array('id');



        public function SetDefaultFields($user) {
            parent::SetDefaultFields($user);
            $this->sort = time();
        }



}