<?php

namespace Tests\GBProd\DoctrineSpecification;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Andx as ExprAndx;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;
use GBProd\DoctrineSpecification\ExpressionBuilder\AndXBuilder;
use GBProd\DoctrineSpecification\ExpressionBuilder\Builder;
use GBProd\DoctrineSpecification\Registry;
use GBProd\Specification\AndX;
use GBProd\Specification\Specification;

class AndXBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        new AndXBuilder(new Registry());
    }

    public function testBuildReturnsAndxExpression()
    {
        $andx = $this->createAndX();
        $registry = $this->createRegistryForAndX($andx);

        $builder = new AndXBuilder($registry);

        $expr = $builder->build($andx, $this->getQueryBuilder());

        $this->assertInstanceOf(ExprAndx::class, $expr);
    }

    private function createAndX()
    {
        return new AndX(
            $this->getMock(Specification::class),
            $this->getMock(Specification::class)
        );
    }

    private function createRegistryForAndX($andx)
    {
        $registry = new Registry();

        $registry->register(
            get_class($andx->getFirstPart()),
            $this->getMock(Builder::class)
        );

        $registry->register(
            get_class($andx->getSecondPart()),
            $this->getMock(Builder::class)
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
        $spec = $this->getMock(Specification::class);
        $registry = new Registry();
        $builder = new AndXBuilder($registry);

        $this->setExpectedException('\InvalidArgumentException');

        $expr = $builder->build($spec, $this->getQueryBuilder());
    }

    public function testBuildReturnsAndxExpressionWithBuildedParts()
    {
        $andx = $this->createAndX();
        $registry = $this->createRegistryForAndX($andx);
        $qb = $this->getQueryBuilder();

        $exprFirstPart = new Comparison('4', '=', '4');
        $exprSecondPart = new Comparison('4', '=', '4');

        $registry
            ->getBuilder($andx->getFirstPart())
            ->expects($this->any())
            ->method('build')
            ->with($andx->getFirstPart(), $qb)
            ->willReturn($exprFirstPart)
        ;

        $registry
            ->getBuilder($andx->getSecondPart())
            ->expects($this->any())
            ->method('build')
            ->with($andx->getSecondPart(), $qb)
            ->willReturn($exprSecondPart)
        ;

        $builder = new AndXBuilder($registry);

        $expr = $builder->build($andx, $qb);

        $this->assertEquals($exprFirstPart, $expr->getParts()[0]);
        $this->assertEquals($exprSecondPart, $expr->getParts()[1]);
    }
}
