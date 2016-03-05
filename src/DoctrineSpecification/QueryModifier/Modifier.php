<?php

namespace GBProd\DoctrineSpecification\QueryModifier;

use Doctrine\ORM\QueryBuilder;
use GBProd\Specification\Specification;

/**
 * Modifier for doctrine specifications
 *
 * @author gbprod <contact@gb-prod.fr>
 */
interface Modifier
{
    /**
     * Modify QueryBuilder using Specification
     *
     * @param Specification $spec
     * @param QueryBuilder  $qb
     *
     * @return QueryBuilder
     */
    public function modify(Specification $spec, QueryBuilder $qb);
}