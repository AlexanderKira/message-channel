<?php

namespace App\Traits;

use App\Http\Builders\Sort\QuerySort;
use Illuminate\Database\Eloquent\Builder;

trait Sortable {

    public function scopeSort(Builder $builder, QuerySort $sort)
    {
        $sort->apply($builder);
    }
}
