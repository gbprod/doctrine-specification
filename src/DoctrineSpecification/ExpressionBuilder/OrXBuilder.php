<?php

namespace GBProd\DoctrineSpecification\ExpressionBuilder;

use GBProd\DoctrineSpecification\Registry;
use GBProd\Specification\Specification;
use Doctrine\ORM\QueryBuilder;

class OrXBuilder implements Builder
{
    private $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function build(Specification $spec, QueryBuilder $qb)
    {
        return $qb->expr()->orx(
            $this->registry->getBuilder($spec->getFirstPart())->build($spec->getFirstPart(), $qb),
            $this->registry->getBuilder($spec->getSecondPart())->build($spec->getSecondPart(), $qb)
        );
    }
}