<?php

    namespace App\Http\Composers;

    use Illuminate\Support\Facades\App;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use App\Models\User;


class AfillaterComposer {
    public static function compose($view) {
        $methodName = 'compose' . studly_case(join('-', preg_split('#[\._]#', $view->getName())));

        if (method_exists(__CLASS__, $methodName)) {
            static::$methodName($view);
        }
    }






	public static function composeAdminAfillaterAfilliateMatrix(&$view) {
        $user = Auth::user();
        // $user = User::find(39);
        $subUsers = $user->getSubUsers(8);
        $tariffs = config('tariffs');

        $matrix = (object) ['levels' => [], 'tariffs' => [], 'total' => 0];
        $level = 0;

        foreach ($tariffs as $tarifCode => $tarif) {
            $matrix->tariffs[$tarifCode] = (object) [
                'prefix' => $tarif['prefix'],
                'count' => 0,
            ];
        }

        foreach ($subUsers as $subUserData) {
            $levelData = (object) ['level' => ++$level, 'total' => 0, 'tariffs' => []];

            foreach ($tariffs as $tarifCode => $tarif) {
                $stat = (object) (isset($subUserData[$tarifCode]) ? ['count' => $subUserData[$tarifCode]->count] : ['count' => 0]);

                $levelData->tariffs[] = (object) [
                    'code' => $tarifCode,
                    'prefix' => $tarif['prefix'],
                    'count' => $stat->count,
                    'url' => route('afillate_users', ['level' => $level, 'tariff' => $tarifCode]),
                ];

                $levelData->total += $stat->count;
                $matrix->tariffs[$tarifCode]->count += $stat->count;
                $matrix->total += $stat->count;
            }

            $matrix->levels[] = $levelData;
        }

        $view->with('matrix', $matrix);
    }






}