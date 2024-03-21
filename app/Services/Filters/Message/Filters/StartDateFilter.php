<?php

namespace App\Services\Filters\Message\Filters;

use App\Contracts\Filter;
use Carbon\Carbon;

class StartDateFilter extends Filter
{

    public function apply($query, string $value)
    {
        if ($value) {
            $value = Carbon::parse($value);
            return $query->where('updated_at', '>=', $value);
        }

        return $this->next ? $this->next->apply($query, $value) : $query;
    }
}
