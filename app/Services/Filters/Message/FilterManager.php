<?php

namespace App\Services\Filters\Message;

use App\Services\Filters\Message\Filters\EndDateFilter;
use App\Services\Filters\Message\Filters\StartDateFilter;
use App\Services\Filters\Message\Filters\TypeFilter;
use Illuminate\Database\Eloquent\Builder;

class FilterManager
{
    protected $filters = [];

    public function __construct()
    {
        $this->filters = [
            'type' => new TypeFilter(),
            'start_date' => new StartDateFilter(),
            'end_date' => new EndDateFilter(),
        ];
    }

    public function apply($query, array $filters): Builder
    {
        foreach ($filters as $key => $value) {
            if (array_key_exists($key, $this->filters)) {
                $this->filters[$key]->apply($query, $value);
            }
        }

        return $query;
    }
}
