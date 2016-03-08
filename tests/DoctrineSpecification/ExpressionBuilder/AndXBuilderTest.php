<?php

namespace Tests\GBProd\DoctrineSpecification;

use GBProd\DoctrineSpecification\ExpressionBuilder\AndXBuilder;
use GBProd\DoctrineSpecification\ExpressionBuilder\Builder;
use GBProd\DoctrineSpecification\Registry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Andx as ExprAndx;
use Doctrine\ORM\Query\Expr;
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
        $andx = new AndX(
            $this->getMock(Specification::class),
            $this->getMock(Specification::class)
        );

        $registry = new Registry();
        $registry->register(
            get_class($andx->getFirstPart()),
            $this->getMock(Builder::class)
        );

        $registry->register(
            get_class($andx->getSecondPart()),
            $this->getMock(Builder::class)
        );

        $builder = new AndXBuilder($registry);

        $expr = $builder->build(
            $andx,
            $this->getQueryBuilder()
        );

        $this->assertInstanceOf(ExprAndx::class, $expr);
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
}
