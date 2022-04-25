<?php

namespace App\Services\Filtration;

use Illuminate\Contracts\Database\Query\Builder;

interface FilterContract
{
    public function filter(Builder $query): void;
}
