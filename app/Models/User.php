<?php

namespace App\Models;

use App\Models\CachedModel;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Http\Request;


class User extends CachedModel  implements
    AuthenticatableContract, 
    AuthorizableContract,
    CanResetPasswordContract
{

    use Authenticatable, Authorizable, CanResetPassword, HasApiTokens, Notifiable;

    protected $guarded = array('id');
    public $timestamps = false;
    // protected $fillable = ['name', 'email', 'password',];
    protected $hidden = ['password', 'remember_token',];





	public function getReferalCode() {
		if ($this->referal_code == '') {
		$this->referal_code = substr(md5(uniqid('code' . time())), 4, 8);
		$this->save();
		}
	return $this->referal_code;
	}




	// получение ID  рефералаа по его номеру телефона (например введенного при регистрации)
	public static function getReferalByPhone($phone) {
		if ($phone != '') {
			$phone = preg_replace("/\+8/", "+7", $phone); // мерзкий хак - замена для дебилов которые вводят +8
			$user_ref = static::where('phone', $phone)->orderBy('created', 'desc')->first();
			if ($user_ref) {
				return $user_ref->id;
			}
		}
	return 0;  // нулевой реферрал используется по умолчанию
	}






	protected function getSubUsersLevel($ids) {
	    $query = static::whereIn('ref_id', $ids)
            ->groupBy('tariff')
            ->select('tariff', DB::raw('count(*) as total'), DB::raw('group_concat(`id`) as `ids`'));

	    $result = ['ids' => [], 'stats' => []];

	    foreach ($query->get() as $q) {
	        $result['stats'][$q->tariff] = (object) [
	            'count' => $q->total,
                'ids' => explode(',', $q->ids)
            ];
	        $ids = explode(',', $q->ids);
	        if (!empty($ids)) {
                $result['ids'] = array_merge($result['ids'], $ids);
            }
        }

	    return $result;
    }

	public function getSubUsers($maxLevels = 8) {
        $ids = [$this->id];
	    $stats = [];

	    for ($i = 0; $i < $maxLevels; ++$i) {
	        $subUsers = $this->getSubUsersLevel($ids);
            $ids = $subUsers['ids'];
            if (empty($ids)) break;
	        $stats[] = $subUsers['stats'];
        }

	    return $stats;
    }


    public function getSubUsersTarifLevel($level, $tarif) {
        $subUsers = $this->getSubUsers();
        $userIds = $subUsers[$level - 1][$tarif]->ids ?? [];

        $users = [];

        if (count($userIds)) {
            $query = static::whereIn('id', $userIds);
            foreach ($query->get() as $q) {
                $users[] = $q;
            }
        }

        return $users;
    }













/*########## получение массива родителей-реферралов "вниз" от текущего юзера ################*/
	public static function getReferalArrayLevels($userId, $toarray = false) {
		global $refAll;

		$allUsers = static::select('ref_id', 'id', 'name')->where('ref_id', '!=', '')->get()->toArray();
		if (count($allUsers) == 0) {return false;}

		$refAll = Array();
		static::getReferalCountRecurciveCheck(0, $allUsers, $userId, $toarray);

		return $refAll;
	}
/*########## получение массива родителей-реферралов "вверх" от текущего юзера ################*/
	public static function getReferalArrayLevelsUp($userId, $toarray = false) {
		global $refAll;

		$allUsers = static::select('ref_id', 'id', 'name')->where('ref_id', '!=', '')->get()->toArray();
		if (count($allUsers) == 0) {return false;}

		$refAll = Array();
		static::getReferalCountRecurciveCheckUp(7, $allUsers, $userId, $toarray);

		return $refAll;
	}
/*######## служебная реккурентная: getReferalCountRecurciveCheck ################*/
	protected static function getReferalCountRecurciveCheck($level, $allUsers, $userId, $toarray = false) {
		global $refAll;
		foreach ($allUsers as $usr) {
			if ($usr['ref_id'] == $userId) {
			    if ($toarray) {
                    $refAll[] = $usr['id'];
                } else {
                    $refAll[ $level ][ $usr['id'] ] = $usr['name'];
                }
				static::getReferalCountRecurciveCheck($level+1, $allUsers, $usr['id'], $toarray);
			}
		}
	}
/*######## служебная реккурентная: getReferalCountRecurciveCheckUp ################*/
	protected static function getReferalCountRecurciveCheckUp($level, $allUsers, $userId, $toarray = false) {
		global $refAll;
		foreach ($allUsers as $usr) {
			if ($usr['id'] == $userId) {
                if ($toarray) {
                    $refAll[] = $usr['id'];
                } else {
                    $refAll[ $level ][ $usr['ref_id'] ] = $usr['name'];
                }
				static::getReferalCountRecurciveCheckUp($level-1, $allUsers, $usr['ref_id'], $toarray);
			}
		}
	}
/*############################################################################*/














/*############## получение всех параметров заданного юзера #######################################*/
	public static function getUser($userId) {
		return static::where('id', $userId)->first();
	}

/*############## получение аватара заданного юзера #######################################*/
	public static function getAvatar($userId, $size='small') {
		if ($size == 'small') {		$prefix = "48x48.";}
		elseif ($size == 'medium') {	$prefix = "150x150.";}
		elseif ($size == 'large') {		$prefix = "500x500.";}
		else {						$prefix = "";}

		$myUser = static::where('id', $userId)->first();
		if ($myUser->avatar) {
			return "/storage/avatars/" . $prefix . $myUser->avatar;
		} else {
			return "/img/signs/" . $prefix . "nophoto.png";
		}
			
	}






/*
	// вроде бы не используется
	public function hasVerifiedEmail() {
		return $this->confirm == 'on';
	}

	// вроде бы не используется
	public function markEmailAsVerified() {
		$this->confirm = 'on';
		$this->save();
		return true;
	}

	// вроде бы не используется
	public function sendEmailVerificationNotification() {
	}
*/

/*
	public static function getReferalArray($userId) {
		$query = static::where('ref_id', $userId);

                $myIncome = static::where('ref_id', $userId);


	$this->referal_code = substr(md5(uniqid('code' . time())), 4, 8);

	return $this->referal_code;
	}
*/



}
