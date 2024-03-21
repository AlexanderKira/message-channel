<?php

namespace App\Contracts;

abstract class Filter
{
    protected $next;

    abstract public function apply($query, string $value);
}
