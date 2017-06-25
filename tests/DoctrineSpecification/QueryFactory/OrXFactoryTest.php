<?php

namespace Tests\GBProd\DoctrineSpecification;

use GBProd\DoctrineSpecification\QueryFactory\OrXFactory;
use GBProd\DoctrineSpecification\QueryFactory\Factory;
use GBProd\DoctrineSpecification\Registry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\OrX as ExprOrX;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Query\Expr;
use GBProd\Specification\OrX;
use GBProd\Specification\Specification;

class OrXFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $factory = new OrXFactory(new Registry());

        $this->assertInstanceOf(OrXFactory::class, $factory);
    }

    public function testBuildReturnsOrxExpression()
    {
        $orx = $this->createOrX();
        $registry = $this->createRegistryForOrX($orx);

        $factory = new OrXFactory($registry);

        $expr = $factory->create(
            $orx,
            $this->getQueryFactory()
        );

        $this->assertInstanceOf(ExprOrX::class, $expr);
    }

    private function createOrX()
    {
        return new OrX(
            $this->prophesize(Specification::class)->reveal(),
            $this->prophesize(Specification::class)->reveal()
        );
    }

    private function createRegistryForOrX($orx)
    {
        $registry = new Registry();

        $registry->register(
            get_class($orx->getFirstPart()),
            $this->createMock(Factory::class)
        );

        $registry->register(
            get_class($orx->getSecondPart()),
            $this->createMock(Factory::class)
        );

        return $registry;
    }

    private function getQueryFactory()
    {
        $qb = $this
            ->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $qb
            ->expects($this->any())
            ->method('expr')
            ->willReturn(new Expr())
        ;

        return $qb;
    }

    public function testBuildThrowExceptionIfNotOrXSpecification()
    {
        $spec = $this->createMock(Specification::class);
        $registry = new Registry();
        $factory = new OrXFactory($registry);

        $this->setExpectedException(\InvalidArgumentException::class);

        $expr = $factory->create($spec, $this->getQueryFactory());
    }

    public function testBuildReturnsOrxExpressionWithBuildedParts()
    {
        $orx = $this->createOrX();

        $registry = new Registry();

        $factoryFirstPart = $this->prophesize(Factory::class);
        $factorySecondPart = $this->prophesize(Factory::class);

        $registry->register(
            get_class($orx->getFirstPart()),
            $factoryFirstPart->reveal()
        );

        $registry->register(
            get_class($orx->getSecondPart()),
            $factorySecondPart->reveal()
        );

        $qb = $this->getQueryFactory();

        $exprFirstPart = new Comparison('4', '=', '4');
        $exprSecondPart = new Comparison('4', '=', '3');

        $factoryFirstPart
            ->create($orx->getFirstPart(), $qb)
            ->willReturn($exprFirstPart)
        ;

        $factorySecondPart
            ->create($orx->getSecondPart(), $qb)
            ->willReturn($exprSecondPart)
        ;

        $factory = new OrXFactory($registry);

        $expr = $factory->create($orx, $qb);

        $this->assertEquals($exprFirstPart, $expr->getParts()[0]);
        $this->assertEquals($exprSecondPart, $expr->getParts()[1]);
    }
}
