<?php

namespace Tests\GBProd\DoctrineSpecification;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Andx as ExprAndx;
use Doctrine\ORM\Query\Expr\Comparison;
use GBProd\DoctrineSpecification\QueryFactory\AndXFactory;
use GBProd\DoctrineSpecification\QueryFactory\Factory;
use GBProd\DoctrineSpecification\Registry;
use GBProd\Specification\AndX;
use GBProd\Specification\Specification;
use PHPUnit\Framework\TestCase;

class AndXFactoryTest extends TestCase
{
    public function testConstruct()
    {
        $factory = new AndXFactory(new Registry());

        $this->assertInstanceOf(AndXFactory::class, $factory);
    }

    public function testBuildReturnsAndxExpression()
    {
        $andx = $this->createAndX();
        $registry = $this->createRegistryForAndX($andx);

        $factory = new AndXFactory($registry);

        $expr = $factory->create($andx, $this->getQueryBuilder());

        $this->assertInstanceOf(ExprAndx::class, $expr);
    }

    private function createAndX()
    {
        return new AndX(
            $this->prophesize(Specification::class)->reveal(),
            $this->prophesize(Specification::class)->reveal()
        );
    }

    private function createRegistryForAndX($andx)
    {
        $registry = new Registry();

        $registry->register(
            get_class($andx->getFirstPart()),
            $this->prophesize(Factory::class)->reveal()
        );

        $registry->register(
            get_class($andx->getSecondPart()),
            $this->prophesize(Factory::class)->reveal()
        );

        return $registry;
    }

    private function getQueryBuilder()
    {
        $qb = $this->getMockBuilder(QueryBuilder::class)
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

    public function testBuildThrowExceptionIfNotAndXSpecification()
    {
        $spec = $this->createMock(Specification::class);
        $registry = new Registry();
        $factory = new AndXFactory($registry);

        $this->expectException(\InvalidArgumentException::class);

        $expr = $factory->create($spec, $this->getQueryBuilder());
    }

    public function testBuildReturnsAndxExpressionWithBuildedParts()
    {
        $andx = $this->createAndX();
        $registry = new Registry();
        $qb = $this->getQueryBuilder();

        $exprFirstPart = new Comparison('4', '=', '4');
        $exprSecondPart = new Comparison('4', '=', '3');

        $factoryFirstPart = $this->prophesize(Factory::class);
        $factorySecondPart = $this->prophesize(Factory::class);

        $registry->register(
            get_class($andx->getFirstPart()),
            $factoryFirstPart->reveal()
        );

        $registry->register(
            get_class($andx->getSecondPart()),
            $factorySecondPart->reveal()
        );

        $factoryFirstPart
            ->create($andx->getFirstPart(), $qb)
            ->willReturn($exprFirstPart)
        ;

        $factorySecondPart
            ->create($andx->getSecondPart(), $qb)
            ->willReturn($exprSecondPart)
        ;

        $factory = new AndXFactory($registry);

        $expr = $factory->create($andx, $qb);

        $this->assertEquals($exprFirstPart, $expr->getParts()[0]);
        $this->assertEquals($exprSecondPart, $expr->getParts()[1]);
    }
}
