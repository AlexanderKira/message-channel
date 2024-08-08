<?php

namespace App\Traits;

use App\Http\Builders\Filter\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

trait Filterable {

    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        $filter->apply($builder);
    }
}
