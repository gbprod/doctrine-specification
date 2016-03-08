<?php

namespace Tests\GBProd\DoctrineSpecification;

use GBProd\DoctrineSpecification\ExpressionBuilder\OrXBuilder;
use GBProd\DoctrineSpecification\ExpressionBuilder\Builder;
use GBProd\DoctrineSpecification\Registry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\OrX as ExprOrX;
use Doctrine\ORM\Query\Expr;
use GBProd\Specification\OrX;
use GBProd\Specification\Specification;

class OrXBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        new OrXBuilder(new Registry());
    }

    public function testBuildReturnsOrxExpression()
    {
        $orx = new OrX(
            $this->getMock(Specification::class),
            $this->getMock(Specification::class)
        );

        $registry = new Registry();
        $registry->register(
            get_class($orx->getFirstPart()),
            $this->getMock(Builder::class)
        );

        $registry->register(
            get_class($orx->getSecondPart()),
            $this->getMock(Builder::class)
        );

        $builder = new OrXBuilder($registry);

        $expr = $builder->build(
            $orx,
            $this->getQueryBuilder()
        );

        $this->assertInstanceOf(ExprOrX::class, $expr);
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

    public function testBuildThrowExceptionIfNotOrXSpecification()
    {
        $spec = $this->getMock(Specification::class);
        $registry = new Registry();
        $builder = new OrXBuilder($registry);

        $this->setExpectedException('\InvalidArgumentException');

        $expr = $builder->build($spec, $this->getQueryBuilder());
    }
}
