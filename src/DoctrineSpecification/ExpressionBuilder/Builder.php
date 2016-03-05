<?php

namespace GBProd\DoctrineSpecification\ExpressionBuilder;

use GBProd\Specification\Specification;
use Doctrine\ORM\QueryBuilder;

interface Builder
{
    public function build(Specification $spec, QueryBuilder $qb);
}