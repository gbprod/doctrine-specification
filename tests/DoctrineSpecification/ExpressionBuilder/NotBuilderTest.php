<?php

namespace Tests\GBProd\DoctrineSpecification;

use GBProd\DoctrineSpecification\ExpressionBuilder\NotBuilder;
use GBProd\DoctrineSpecification\ExpressionBuilder\Builder;
use GBProd\DoctrineSpecification\Registry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Func as ExprNot;
use Doctrine\ORM\Query\Expr;
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
}
