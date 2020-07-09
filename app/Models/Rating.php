<?php

namespace App\Models;

use App\Exceptions\RatingException;
use App\Libs\Helpers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Rating extends CachedModel {
        protected $table = 'rating';
        public $timestamps = false;
        protected $guarded = array('id');



        protected static function getRatingCacheKey($userId) {
            return 'user:' . $userId . ':rating';
        }



	// внесение платежа в рейтинг
        public static function addPayment($userId, $sum, $description = '') {
            $item = new static();
            $item->fill(['user_id' => $userId, 'sum' => $sum, 'description' => $description, 'created' => Helpers::Now()]);

            if (!$item->save()) {
                throw new RatingException('Unable to add payment', RatingException::GENERAL_ERROR);
            }

            Cache::forget(static::getRatingCacheKey($userId));
        }



	// получение списка всех бонусов
        public static function getExtendedHistory($userId) {
            $query = static::where('user_id', $userId)->orderBy('created', 'desc');

            return $query->get();
        }



	// получение текущего рейтинга юзера
	public static function getUserRating($userId, $clearCache = false) {
		if ($clearCache) {
			Cache::forget(static::getRatingCacheKey($userId));
		}

            return Cache::remember(static::getRatingCacheKey($userId), 60, function() use ($userId) {
			$result = (object)Array();
			
			$total = static::where('user_id', $userId)->sum('sum') - 0;
			$result->total = $total;

			$ratings = config('ratings');
			foreach ($ratings as $rating) {
				if ($total >= $rating['price_from'] && $total < $rating['price_to']) {
					$result->name = $rating['name'];
					$result->logo = $rating['logo'];
					$result->price_to = $rating['price_to'];
					break;
				}
			}

			return $result;
            });
	}






    /**
     * Начисляет рейтинг при покупке тарифа всем уровням рефералов
     * @param $userId integer идентификатор пользователя, купившего пакет
     * @param $sum integer сумма покупки
     * @return null
     */

	public static function payRatingToReferrals($userId, $testOnly = false) {
		$ratingMatrix = config('bonuses.rating_tariffs');

        $level = 1;
        $user = User::find($userId);
	    $descriptionTariff = $user->tariff;
	    $descriptionUserId = $user->id;

        while ($level <= 8 && $user) {
            if (empty($user->ref_id)) break;
            $user = User::find($user->ref_id);
            if (!$user) break;

            if ($testOnly) {print "user->id = $user->id / user->tariff = $user->tariff / level = $level \n";}

            // премерский хак когда прерывается цикл в зависимости от тарифа афиллата которому происходит начисление
            if (
                ($user->tariff == 'STANDARD') ||
                ($user->tariff == 'GOLD') ||
                ($user->tariff == 'BUSINESS OPTIMUM' && $level > 5) ||
                ($user->tariff == 'BUSINESS START' && $level > 3) ||
                (!$user->tariff)
            ) {++$level; continue;}

            // берётся тариф который изначально был куплен
            $ratingSum = $ratingMatrix[$descriptionTariff][$level] ?? 0;
            if ($ratingSum > 0) {

                $ratingDescription = 'На твоем тарифе "' . $user->tariff . '" начислен рейтинг за покупку тарифа "' . $descriptionTariff . '" пользователем ID ' . $descriptionUserId .' на партнёрском уровне ' . $level;
                if ($testOnly) {print "$ratingSum BALL - $ratingDescription\n";}

                $rating = new Rating();
                $rating->fill([
                    'user_id' => $user->id,
                    'created' => date('Y-m-d H:i:s'),
                    'sum' => $ratingSum,
                    'description' => $ratingDescription,
                    'data' => json_encode(['user_id' => $userId]),
                ]);
                if (!$testOnly) {
                    $rating->save();
                }

            }
            if ($testOnly) {print "To user $user->id - from user $descriptionUserId tariff $descriptionTariff - in level $level - rating $ratingSum %\n\n";}
            ++$level;
        }
        if ($testOnly) {exit;}
    }






}