<?php

namespace App\Services\Filtration\Filters;

use Illuminate\Contracts\Database\Query\Builder;

class SeasonStartsThisMonth extends AbstractFilter
{

    public function filter(Builder $query): void
    {
        $monthStart = date("Y-m-d", strtotime("first day of this month"));
        $monthEnd = date("Y-m-d", strtotime("last day of this month"));
        $query->join("seasons", "film_id", "=", "films.id")
            ->whereBetween("seasons.start_date", [$monthStart, $monthEnd]);
    }
}
