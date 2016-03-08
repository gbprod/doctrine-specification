<?php

namespace Tests\GBProd\DoctrineSpecification;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Query\Expr\Func as ExprNot;
use Doctrine\ORM\QueryBuilder;
use GBProd\DoctrineSpecification\ExpressionBuilder\Builder;
use GBProd\DoctrineSpecification\ExpressionBuilder\NotBuilder;
use GBProd\DoctrineSpecification\Registry;
use GBProd\Specification\Not;
use GBProd\Specification\Specification;

class NotBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        new NotBuilder(new Registry());
    }

    public function testBuildReturnsNotExpression()
    {
        $not = new Not($this->getMock(Specification::class));

        $registry = new Registry();
        $registry->register(
            get_class($not->getWrappedSpecification()),
            $this->getMock(Builder::class)
        );

        $builder = new NotBuilder($registry);

        $expr = $builder->build(
            $not,
            $this->getQueryBuilder()
        );

        $this->assertInstanceOf(ExprNot::class, $expr);
    }

    private function getQueryBuilder()
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

    public function testBuildThrowExceptionIfNotNotSpecification()
    {
        $spec = $this->getMock(Specification::class);
        $registry = new Registry();
        $builder = new NotBuilder($registry);

        $this->setExpectedException('\InvalidArgumentException');

        $expr = $builder->build($spec, $this->getQueryBuilder());
    }

    public function testBuildReturnsOrxExpressionWithBuildedParts()
    {
        $not = new Not($this->getMock(Specification::class));

        $registry = new Registry();
        $registry->register(
            get_class($not->getWrappedSpecification()),
            $this->getMock(Builder::class)
        );

        $qb = $this->getQueryBuilder();

        $exprFirstPart = new Comparison('4', '=', '4');

        $registry
            ->getBuilder($not->getWrappedSpecification())
            ->expects($this->any())
            ->method('build')
            ->with($not->getWrappedSpecification(), $qb)
            ->willReturn($exprFirstPart)
        ;

        $builder = new NotBuilder($registry);

        $expr = $builder->build($not, $qb);

        $this->assertEquals($exprFirstPart, $expr->getArguments()[0]);
    }
}
