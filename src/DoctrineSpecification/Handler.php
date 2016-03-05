<?php

namespace GBProd\DoctrineSpecification;

use Doctrine\ORM\QueryBuilder;
use GBProd\DoctrineSpecification\QueryModifier\Modifier;
use GBProd\Specification\Specification;

/**
 * Handler for doctrine specifications
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class Handler
{
    /**
     * @param array<Modifier>
     */
    private $modifiers = array();

    /**
     * handle specification for querybuilder
     *
     * @param Specification $spec
     * @param QueryBuilder  $qb
     *
     * @return QueryBuilder
     */
    public function handle(Specification $spec, QueryBuilder $qb)
    {
        $modifier = $this->getModifierForSpecification($spec);

        $modifier->modify($spec, $qb);
    }

    private function getModifierForSpecification(Specification $spec)
    {
        if (!isset($this->modifiers[get_class($spec)])) {
            throw new \OutOfRangeException(
                sprintf(
                    'Modifier for "%s" specification not found',
                    get_class($spec)
                )
            );
        }

        return $this->modifiers[get_class($spec)];
    }

    /**
     * Register a modifier for specification
     *
     * @param string $classname specification fully qualified classname
     * @param Modifier $modifier
     */
    public function registerModifier($classname, Modifier $modifier)
    {
        $this->modifiers[$classname] = $modifier;
    }
}