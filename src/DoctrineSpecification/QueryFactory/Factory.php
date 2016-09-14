<?php

namespace GBProd\DoctrineSpecification\QueryFactory;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Base;
use GBProd\Specification\Specification;

/**
 * Interface for Doctrine Expression Factories
 *
 * @author gbprod <contact@gb-prod.fr>
 */
interface Factory
{
    /**
     * Create query for specification
     *
     * @param Specification $spec
     * @param QueryBuilder  $qb
     *
     * @return Base
     */
    public function create(Specification $spec, QueryBuilder $qb);
}
