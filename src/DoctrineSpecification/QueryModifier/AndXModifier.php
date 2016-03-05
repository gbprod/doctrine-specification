<?php

namespace GBProd\DoctrineSpecification\QueryModifier;

use Doctrine\ORM\QueryBuilder;
use GBProd\DoctrineSpecification\Handler;
use GBProd\Specification\Specification;

/**
 * Modifier for AndX specifications
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class AndXModifier implements Modifier
{
    /**
     * @var Handler
     */
    private $handler;

    /**
     * @param Handler $handler
     */
    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * {inheritdoc}
     */
    public function modify(Specification $spec, QueryBuilder $qb)
    {
        return $qb->expr()->andx(
            $this->handler->handle($spec->getFirstPart(), $qb),
            $this->handler->handle($spec->getSecondPart(), $qb)
        );
    }
}