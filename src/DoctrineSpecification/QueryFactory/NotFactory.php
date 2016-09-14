<?php

namespace GBProd\DoctrineSpecification\QueryFactory;

use GBProd\DoctrineSpecification\Registry;
use GBProd\Specification\Not;
use GBProd\Specification\Specification;
use Doctrine\ORM\QueryBuilder;

/**
 * Factory for Not specification
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class NotFactory implements Factory
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
    public function create(Specification $spec, QueryBuilder $qb)
    {
        if (!$spec instanceof Not) {
            throw new \InvalidArgumentException();
        }

        $factory = $this->registry->getFactory($spec->getWrappedSpecification());

        return $qb->expr()->not(
            $factory->create($spec->getWrappedSpecification(), $qb)
        );
    }
}
