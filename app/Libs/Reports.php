<?php

    namespace App\Libs;

    use App\Models\BillingTariffs;
    use Illuminate\Support\Facades\Auth;

    class Reports {


        public function getTariffReport($userId) {
            $subUsers = Auth::user()->getSubUsers(8);

            $flatIds = [];
            $id2level = [];
            $maxLevel = 0;

            foreach ($subUsers as $levelId => $level) {
                $maxLevel = max($levelId, $maxLevel);
                foreach ($level as $data) {
                    $flatIds = array_merge($flatIds, $data->ids);
                    foreach ($data->ids as $id) {
                        $id2level[$id] = $levelId;
                    }
                }
            }

            $matrix = (object) [
                'tariffs' => [],
                'levels' => [],
                'count' => 0,
                'sum' => 0,
            ];
            $tariffs = config('tariffs');

            for ($level = 0; $level < $maxLevel; ++$level) {
                $matrixRow = [];
                foreach (array_keys($tariffs) as $tariff) {
                    $matrixRow[$tariff] = (object) ['count' => 0, 'sum' => 0];
                    $matrix->levels[$level] = (object) ['count' => 0, 'sum' => 0, 'tariffs' => $matrixRow];
                }
            }

            foreach (array_keys($tariffs) as $tariff) {
                $matrix->tariffs[$tariff] = (object) ['count' => 0, 'sum' => 0];
            }

            $sold = BillingTariffs::whereIn('user_id', $flatIds);
            foreach ($sold->get() as $s) {
                $levelId = $id2level[$s->user_id];
                if (!isset($matrix->levels[$levelId]->tariffs[$s->tariff])) continue;

                ++$matrix->count;
                ++$matrix->levels[$levelId]->count;
                ++$matrix->levels[$levelId]->tariffs[$s->tariff]->count;
                ++$matrix->tariffs[$s->tariff]->count;
                $matrix->sum += $s->sum;
                $matrix->levels[$levelId]->sum += $s->sum;
                $matrix->levels[$levelId]->tariffs[$s->tariff]->sum += $s->sum;
                $matrix->tariffs[$s->tariff]->sum += $s->sum;
            }

            return [
                'tariffs' => $tariffs,
                'matrix' => $matrix,
            ];
        }
    }
