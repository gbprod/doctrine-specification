<?php

namespace GBProd\DoctrineSpecification;

use Doctrine\ORM\QueryBuilder;
use GBProd\DoctrineSpecification\ExpressionBuilder\AndXBuilder;
use GBProd\DoctrineSpecification\ExpressionBuilder\Builder;
use GBProd\DoctrineSpecification\ExpressionBuilder\NotBuilder;
use GBProd\DoctrineSpecification\ExpressionBuilder\OrXBuilder;
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

        $this->registry->register(AndX::class, new AndXBuilder($registry));
        $this->registry->register(OrX::class, new OrXBuilder($registry));
        $this->registry->register(Not::class, new NotBuilder($registry));
    }

    /**
     * handle specification for querybuilder
     *
     * @param Specification $spec
     * @param QueryBuilder  $qb
     *
     * @return QueryBuilder
     */
    public function handle(Specification $spec, QueryBuilder $qb)
    {
        $builder = $this->registry->getBuilder($spec);

        $qb->where(
            $builder->build($spec, $qb)
        );

        return $qb->getQuery()->getResult();
    }

    /**
     * Register a modifier for specification
     *
     * @param string $classname specification fully qualified classname
     * @param Modifier $builder
     */
    public function registerBuilder($classname, Builder $builder)
    {
        $this->registry->register($classname, $builder);
    }
}