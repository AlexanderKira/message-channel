<?php

namespace App\Services\Filters\Message\Filters;

use App\Contracts\Filter;

class TypeFilter extends Filter
{

    public function apply($query, string $value)
    {
        if ($value) {
            return $query->where('type', $value);
        }

        return $this->next ? $this->next->apply($query, $value) : $query;
    }
}
