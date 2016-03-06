<?php

namespace GBProd\DoctrineSpecification;

use GBProd\DoctrineSpecification\ExpressionBuilder\Builder;
use GBProd\Specification\Specification;

/**
 * Registry class for Expression builders
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class Registry
{
    /**
     * @var array<Builder>
     */
    private $builders = array();

    /**
     * Register a builder
     *
     * @param string classname Fully qualified classname of the handled specification
     * @param Builder $builder
     */
    public function register($classname, Builder $builder)
    {
        $this->builders[$classname] = $builder;
    }

    /**
     * Get registred builder for Specification
     *
     * @param Specification $spec
     *
     * @return Builder
     *
     * @throw OutOfRangeException if builder not found
     */
    public function getBuilder(Specification $spec)
    {
        if(!isset($this->builders[get_class($spec)])) {
            throw new \OutOfRangeException(sprintf(
                'Builder for Specification "%s" not registred',
                get_class($spec)
            ));
        }

        return $this->builders[get_class($spec)];
    }
}