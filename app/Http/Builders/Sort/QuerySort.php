<?php

namespace App\Http\Builders\Sort;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Builders\QueryBuilders;

abstract class QuerySort extends QueryBuilders
{
    protected function fields(): array
    {
        return array_filter(
            array_map('trim', $this->request->json('sort') != null ? $this->request->json('sort') : [])
        );
    }
}

