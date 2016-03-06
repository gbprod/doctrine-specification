<?php

namespace Tests\GBProd\DoctrineSpecification;

use GBProd\DoctrineSpecification\Registry;
use GBProd\Specification\Specification;

/**
 * Tests for registry
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class RegistryTest extends \PHPUnit_Framework_TestCase implements Specification
{
    public function testConstruct()
    {
        new Registry();
    }

    public function isSatisfiedBy($candidate)
    {
        return true;
    }

    public function testGetBuilderThrowsOutOfRangeExceptionIfBuilderNotRegistred()
    {
        $registry = new Registry();

        $this->setExpectedException('\OutOfRangeException');

        $registry->getBuilder($this);
    }
    public function testGetBuilderReturnsAssociatedBuilder()
    {
        $registry = new Registry();

        $builder = $this->getMock('GBprod\DoctrineSpecification\ExpressionBuilder\Builder');

        $registry->register(self::class, $builder);

        $this->assertEquals(
            $builder,
            $registry->getBuilder($this)
        );
    }
}
