<?php

namespace Tests\GBProd\DoctrineSpecification;

use GBProd\DoctrineSpecification\QueryFactory\Factory;
use GBProd\DoctrineSpecification\Registry;
use GBProd\Specification\Specification;
use PHPUnit\Framework\TestCase;

/**
 * Tests for registry
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class RegistryTest extends TestCase implements Specification
{
    public function testConstruct()
    {
        $registry = new Registry();

        $this->assertInstanceOf(Registry::class, $registry);
    }

    public function isSatisfiedBy($candidate)
    {
        return true;
    }

    public function testGetFactoryThrowsOutOfRangeExceptionIfFactoryNotRegistred()
    {
        $registry = new Registry();

        $this->expectException(\OutOfRangeException::class);

        $registry->getFactory($this);
    }

    public function testGetFactoryReturnsAssociatedFactory()
    {
        $registry = new Registry();

        $factory = $this->createMock(Factory::class);

        $registry->register(self::class, $factory);

        $this->assertEquals(
            $factory,
            $registry->getFactory($this)
        );
    }
}
