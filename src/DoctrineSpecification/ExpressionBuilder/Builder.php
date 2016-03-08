<?php

namespace GBProd\DoctrineSpecification\ExpressionBuilder;

use GBProd\Specification\Specification;
use Doctrine\ORM\QueryBuilder;

/**
 * Interface for Doctrine Expression Builders
 *
 * @author gbprod <contact@gb-prod.fr>
 */
interface Builder
{
    /**
     * Build expression for specification
     *
     * @param Specification $spec
     * @param QueryBuilder  $qb
     *
     * @return Doctrine\ORM\Query\Expr\Base
     */
    public function build(Specification $spec, QueryBuilder $qb);
}