<?php

namespace Tests\GBProd\DoctrineSpecification;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Query\Expr\Func as ExprNot;
use Doctrine\ORM\QueryBuilder;
use GBProd\DoctrineSpecification\QueryFactory\Factory;
use GBProd\DoctrineSpecification\QueryFactory\NotFactory;
use GBProd\DoctrineSpecification\Registry;
use GBProd\Specification\Not;
use GBProd\Specification\Specification;

class NotFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        new NotFactory(new Registry());
    }

    public function testBuildReturnsNotExpression()
    {
        $not = new Not($this->getMock(Specification::class));

        $registry = new Registry();
        $registry->register(
            get_class($not->getWrappedSpecification()),
            $this->getMock(Factory::class)
        );

        $factory = new NotFactory($registry);

        $expr = $factory->create(
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
        $factory = new NotFactory($registry);

        $this->setExpectedException('\InvalidArgumentException');

        $expr = $factory->create($spec, $this->getQueryBuilder());
    }

    public function testBuildReturnsOrxExpressionWithBuildedParts()
    {
        $not = new Not($this->getMock(Specification::class));

        $registry = new Registry();
        $registry->register(
            get_class($not->getWrappedSpecification()),
            $this->getMock(Factory::class)
        );

        $qb = $this->getQueryBuilder();

        $exprFirstPart = new Comparison('4', '=', '4');

        $registry
            ->getFactory($not->getWrappedSpecification())
            ->expects($this->any())
            ->method('create')
            ->with($not->getWrappedSpecification(), $qb)
            ->willReturn($exprFirstPart)
        ;

        $factory = new NotFactory($registry);

        $expr = $factory->create($not, $qb);

        $this->assertEquals($exprFirstPart, $expr->getArguments()[0]);
    }
}
