<?php

namespace App\Filters;

use ReflectionClass:
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class Filter
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * Filter constructor.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get all available filter methods.
     *
     * @return array
     * @throws \ReflectionException
     */
    protected function getFilterMethods()
    {
        $class = new ReflectionClass(static::class);

        $methods = array_map(function($method) use ($class) {
            if ($method->class === $class->getName()) {
                return $method->name;
            }
            return null;
        }, $class->getMethods());

        return array_filter($methods);
    }

    /**
     * Get all the filters that can be applied.
     *
     * @return mixed
     * @throws \ReflectionException
     */
    protected function getFilters()
    {
        return $this->request->inspect($this->getFilterMethods());
    }

    /**
     * Apply all the requested filters if available.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \ReflectionException
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $name => $value) {
            if (method_exists($this, $name)) {
                $this->$name($value);
            }
        }

        return $this->builder;
    }
}
