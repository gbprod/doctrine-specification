<?php

namespace Tests\GBProd\DoctrineSpecification;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Base;
use Doctrine\ORM\Query\Expr\Comparison;
use GBProd\DoctrineSpecification\Handler;
use GBProd\DoctrineSpecification\QueryFactory\AndXFactory;
use GBProd\DoctrineSpecification\QueryFactory\Factory;
use GBProd\DoctrineSpecification\QueryFactory\NotFactory;
use GBProd\DoctrineSpecification\QueryFactory\OrXFactory;
use GBProd\DoctrineSpecification\Registry;
use GBProd\Specification\AndX;
use GBProd\Specification\Not;
use GBProd\Specification\OrX;
use GBProd\Specification\Specification;
use PHPUnit\Framework\TestCase;

class HandlerTest extends TestCase
{
    private $registry;

    private $qb;

    private $handler;

    public function setUp()
    {
        $this->registry = new Registry();
        $this->qb = $this->prophesize(QueryBuilder::class)->reveal();

        $this->handler = new Handler($this->registry, $this->qb);
    }

    public function testConstructWillRegisterBaseFactorys()
    {
        $spec1 = $this->createMock(Specification::class);
        $spec2 = $this->createMock(Specification::class);

        $this->assertInstanceOf(
            AndXFactory::class,
            $this->registry->getFactory(new AndX($spec1, $spec2))
        );

        $this->assertInstanceOf(
            OrXFactory::class,
            $this->registry->getFactory(new OrX($spec1, $spec2))
        );

        $this->assertInstanceOf(
            NotFactory::class,
            $this->registry->getFactory(new Not($spec1))
        );
    }

    public function testRegisterWillAddFactoryToRegistry()
    {
        $factory = $this->prophesize(Factory::class)->reveal();
        $spec = $this->prophesize(Specification::class)->reveal();

        $this->handler->registerFactory(get_class($spec), $factory);

        $this->assertEquals($factory, $this->registry->getFactory($spec));
    }


    public function testHandleReturnsQuery()
    {
        $factory = $this->prophesize(Factory::class);
        $spec = $this->prophesize(Specification::class)->reveal();

        $this->handler->registerFactory(get_class($spec), $factory->reveal());

        $factory
            ->create($spec, $this->qb)
            ->willReturn(new Comparison('42', '=', '42'))
        ;

        $this->assertEquals(new Comparison('42', '=', '42'), $this->handler->handle($spec));
    }
}
