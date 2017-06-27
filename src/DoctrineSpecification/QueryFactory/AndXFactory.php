<?php

declare(strict_types=1);

namespace GBProd\DoctrineSpecification\QueryFactory;

use Doctrine\ORM\QueryBuilder;
use GBProd\DoctrineSpecification\Registry;
use GBProd\Specification\AndX;
use GBProd\Specification\Specification;

/**
 * Factory for AndX specification
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class AndXFactory implements Factory
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
        if (!$spec instanceof AndX) {
            throw new \InvalidArgumentException();
        }

        $firstPartFactory = $this->registry->getFactory($spec->getFirstPart());
        $secondPartFactory = $this->registry->getFactory($spec->getSecondPart());

        return $qb->expr()->andX(
            $firstPartFactory->create($spec->getFirstPart(), $qb),
            $secondPartFactory->create($spec->getSecondPart(), $qb)
        );
    }
}
