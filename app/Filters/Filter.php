<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class Filter
{
   protected $request;

   protected $builder;

   public function __construct(Request $request)
   {
       $this->request = $request;
   }

   protected function getFilterMethods()
   {
       $methods = array_diff(
           get_class_methods(static::class),
           [
               '__construct',
               'getFilterMethods',
               'getFilters',
               'apply',
           ]
       );

       return array_flatten($methods);
   }

   protected function getFilters()
   {
       return $this->request->inspect($this->getFilterMethods());
   }

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
