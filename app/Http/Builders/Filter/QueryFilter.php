<?php

namespace App\Http\Builders\Filter;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Builders\QueryBuilders;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QueryFilter extends QueryBuilders
{
    protected function fields(): array
    {
        if (!empty($this->request['filter']))
        {
            $arrFilter = [];
            foreach ($this->request['filter'] as $key => $row)
            {
                if (is_array($row)) {
                    $arrFilter[$key] = preg_replace('~^(?:[\w\h\pP][?!$]?)+~m', '[$0]', implode(',', $row));
                }
                else
                {
                    $arrFilter[$key] = $row;
                }
            }
            return array_filter(
                array_map('trim', $arrFilter != null ? $arrFilter : [])
            );
        }
        else
        {
            return array_filter(
                array_map('trim', $this->request['filter'] != null ? $this->request['filter'] : [])
            );
        }
    }
}
