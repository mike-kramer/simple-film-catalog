<?php

namespace App\Services\Filtration\Filters;

use Illuminate\Contracts\Database\Query\Builder;

class HasGenre extends AbstractFilter
{

    public function filter(Builder $query): void
    {
        $query->whereHas("genres", function (Builder $query) {
            $query->whereIn("genre_id", $this->filterParams["genres)ids"]);
        });
    }
}
