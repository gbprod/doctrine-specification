<?php

namespace GBProd\DoctrineSpecification\ExpressionBuilder;

use GBProd\DoctrineSpecification\Registry;
use GBProd\Specification\OrX;
use GBProd\Specification\Specification;
use Doctrine\ORM\QueryBuilder;

/**
 * Expression Builder for OrX specification
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class OrXBuilder implements Builder
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
        if (!$spec instanceof OrX) {
            throw new \InvalidArgumentException();
        }

        return $qb->expr()->orx(
            $this->registry->getBuilder($spec->getFirstPart())->build($spec->getFirstPart(), $qb),
            $this->registry->getBuilder($spec->getSecondPart())->build($spec->getSecondPart(), $qb)
        );
    }
}