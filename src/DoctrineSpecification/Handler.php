<?php

namespace GBProd\DoctrineSpecification;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Base;
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
     * @var Registry
     */
    private $registry;

    /**
     * @var QueryBuilder
     */
    private $qb;

    /**
     * @param Registry     $registry
     * @param QueryBuilder $qb
     */
    public function __construct(Registry $registry, QueryBuilder $qb)
    {
        $this->registry = $registry;
        $this->qb       = $qb;

        $this->registry->register(AndX::class, new AndXFactory($registry));
        $this->registry->register(OrX::class, new OrXFactory($registry));
        $this->registry->register(Not::class, new NotFactory($registry));
    }

    /**
     * handle specification for queryfactory
     *
     * @param Specification $spec
     *
     * @return Base
     */
    public function handle(Specification $spec)
    {
        $factory = $this->registry->getFactory($spec);

        return $factory->create($spec, $this->qb);
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
