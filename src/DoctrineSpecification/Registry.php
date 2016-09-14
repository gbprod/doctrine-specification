<?php

namespace GBProd\DoctrineSpecification;

use GBProd\DoctrineSpecification\QueryFactory\Factory;
use GBProd\Specification\Specification;

/**
 * Registry class for Factories
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class Registry
{
    /**
     * @var array<Factory>
     */
    private $factories = array();

    /**
     * Register a factory
     *
     * @param string classname Fully qualified classname of the handled specification
     * @param Factory $factory
     */
    public function register($classname, Factory $factory)
    {
        $this->factories[$classname] = $factory;
    }

    /**
     * Get registred factory for Specification
     *
     * @param Specification $spec
     *
     * @return Factory
     *
     * @throw OutOfRangeException if Factory not found
     */
    public function getFactory(Specification $spec)
    {
        if(!isset($this->factories[get_class($spec)])) {
            throw new \OutOfRangeException(sprintf(
                'Factory for Specification "%s" not registred',
                get_class($spec)
            ));
        }

        return $this->factories[get_class($spec)];
    }
}
