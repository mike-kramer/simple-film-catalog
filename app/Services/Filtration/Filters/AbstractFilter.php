<?php

namespace App\Services\Filtration\Filters;

use App\Services\Filtration\FilterContract;

abstract class AbstractFilter implements FilterContract
{
    public function __construct(protected $filterName, protected $filterParams)
    {
    }
}
