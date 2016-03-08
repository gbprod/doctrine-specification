<?php

namespace GBProd\DoctrineSpecification\ExpressionBuilder;

use GBProd\DoctrineSpecification\Registry;
use GBProd\Specification\AndX;
use GBProd\Specification\Specification;
use Doctrine\ORM\QueryBuilder;

/**
 * Expression Builder for AndX specification
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class AndXBuilder implements Builder
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
        if (!$spec instanceof AndX) {
            throw new \InvalidArgumentException();
        }

        return $qb->expr()->andx(
            $this->registry->getBuilder($spec->getFirstPart())->build($spec->getFirstPart(), $qb),
            $this->registry->getBuilder($spec->getSecondPart())->build($spec->getSecondPart(), $qb)
        );
    }
}