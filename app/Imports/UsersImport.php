<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
//use Maatwebsite\Excel\Concerns\ToCollection;


class UsersImport implements ToModel {
	use Importable;

    //@param array $row
    //@return User|null
    public function model(array $row)
    {
        return new User([
            //
        ]);
    }
}
