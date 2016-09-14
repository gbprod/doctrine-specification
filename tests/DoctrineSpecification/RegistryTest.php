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

    public function testGetFactoryThrowsOutOfRangeExceptionIfFactoryNotRegistred()
    {
        $registry = new Registry();

        $this->setExpectedException('\OutOfRangeException');

        $registry->getFactory($this);
    }
    public function testGetFactoryReturnsAssociatedFactory()
    {
        $registry = new Registry();

        $factory = $this->getMock('GBprod\DoctrineSpecification\QueryFactory\Factory');

        $registry->register(self::class, $factory);

        $this->assertEquals(
            $factory,
            $registry->getFactory($this)
        );
    }
}
