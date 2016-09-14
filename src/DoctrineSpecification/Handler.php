<?php

namespace GBProd\DoctrineSpecification;

use Doctrine\ORM\QueryBuilder;
use GBProd\DoctrineSpecification\QueryFactory\AndXFactory;
use GBProd\DoctrineSpecification\QueryFactory\Factory;
use GBProd\DoctrineSpecification\QueryFactory\NotFactory;
use GBProd\DoctrineSpecification\QueryFactory\OrXFactory;
use GBProd\Specification\AndX;
use GBProd\Specification\Not;
use GBProd\Specification\OrX;
use GBProd\Specification\Specification;

/**
 * Handler for doctrine specifications
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class Handler
{
    /**
     * @param Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;

        $this->registry->register(AndX::class, new AndXFactory($registry));
        $this->registry->register(OrX::class, new OrXFactory($registry));
        $this->registry->register(Not::class, new NotFactory($registry));
    }

    /**
     * handle specification for queryfactory
     *
     * @param Specification $spec
     * @param QueryBuilder  $qb
     *
     * @return array
     */
    public function handle(Specification $spec, QueryBuilder $qb)
    {
        $factory = $this->registry->getFactory($spec);

        $qb->where($factory->create($spec, $qb));

        return $qb->getQuery()->getResult();
    }

    /**
     * Register a factory for specification
     *
     * @param string  $classname specification fully qualified classname
     * @param Factory $factory
     */
    public function registerFactory($classname, Factory $factory)
    {
        $this->registry->register($classname, $factory);
    }
}
