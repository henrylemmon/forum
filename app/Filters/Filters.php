<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    protected $request, $builder;

    protected $filters = [];

    /**
     * ThreadFilters constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply the filters
     *
     * @param $builder
     * @return mixed
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    public function getFilters()
    {
        return $this->request->only($this->filters);
    }
}
