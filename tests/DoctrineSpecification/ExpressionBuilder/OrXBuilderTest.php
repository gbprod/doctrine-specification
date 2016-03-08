<?php

namespace Tests\GBProd\DoctrineSpecification;

use GBProd\DoctrineSpecification\ExpressionBuilder\OrXBuilder;
use GBProd\DoctrineSpecification\ExpressionBuilder\Builder;
use GBProd\DoctrineSpecification\Registry;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\OrX as ExprOrX;
use Doctrine\ORM\Query\Expr\Comparison;
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
        $orx = $this->createOrX();
        $registry = $this->createRegistryForOrX($orx);

        $builder = new OrXBuilder($registry);

        $expr = $builder->build(
            $orx,
            $this->getQueryBuilder()
        );

        $this->assertInstanceOf(ExprOrX::class, $expr);
    }

    private function createOrX()
    {
        return new OrX(
            $this->getMock(Specification::class),
            $this->getMock(Specification::class)
        );
    }

    private function createRegistryForOrX($orx)
    {
        $registry = new Registry();

        $registry->register(
            get_class($orx->getFirstPart()),
            $this->getMock(Builder::class)
        );

        $registry->register(
            get_class($orx->getSecondPart()),
            $this->getMock(Builder::class)
        );

        return $registry;
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

    public function testBuildThrowExceptionIfNotOrXSpecification()
    {
        $spec = $this->getMock(Specification::class);
        $registry = new Registry();
        $builder = new OrXBuilder($registry);

        $this->setExpectedException('\InvalidArgumentException');

        $expr = $builder->build($spec, $this->getQueryBuilder());
    }

    public function testBuildReturnsOrxExpressionWithBuildedParts()
    {
        $orx = $this->createOrX();
        $registry = $this->createRegistryForOrX($orx);
        $qb = $this->getQueryBuilder();

        $exprFirstPart = new Comparison('4', '=', '4');
        $exprSecondPart = new Comparison('4', '=', '4');

        $registry
            ->getBuilder($orx->getFirstPart())
            ->expects($this->any())
            ->method('build')
            ->with($orx->getFirstPart(), $qb)
            ->willReturn($exprFirstPart)
        ;

        $registry
            ->getBuilder($orx->getSecondPart())
            ->expects($this->any())
            ->method('build')
            ->with($orx->getSecondPart(), $qb)
            ->willReturn($exprSecondPart)
        ;

        $builder = new OrXBuilder($registry);

        $expr = $builder->build($orx, $qb);

        $this->assertEquals($exprFirstPart, $expr->getParts()[0]);
        $this->assertEquals($exprSecondPart, $expr->getParts()[1]);
    }
}
