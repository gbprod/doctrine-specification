<?php

namespace GBProd\DoctrineSpecification;

use GBProd\DoctrineSpecification\ExpressionBuilder\Builder;
use GBProd\Specification\Specification;

class Registry
{
    private $builders = array();

    public function register($classname, Builder $builder)
    {
        $this->builders[$classname] = $builder;
    }

    public function getBuilder(Specification $spec)
    {
        if(!isset($this->builders[get_class($spec)])) {
            throw new \OutOfRangeException();
        }

        return $this->builders[get_class($spec)];
    }
}