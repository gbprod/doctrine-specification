<?php

namespace GBProd\DoctrineSpecification\ExpressionBuilder;

use GBProd\DoctrineSpecification\Registry;
use GBProd\Specification\Not;
use GBProd\Specification\Specification;
use Doctrine\ORM\QueryBuilder;

/**
 * Expression Builder for Not specification
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class NotBuilder implements Builder
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {inheritdoc}
     */
    public function build(Specification $spec, QueryBuilder $qb)
    {
        if (!$spec instanceof Not) {
            throw new \InvalidArgumentException();
        }

        return $qb->expr()->not(
            $this->registry->getBuilder($spec->getWrappedSpecification())->build($spec->getWrappedSpecification(), $qb)
        );
    }
}