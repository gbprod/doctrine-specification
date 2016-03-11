<?php

namespace Tests\GBProd\DoctrineSpecification;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Base;
use GBProd\DoctrineSpecification\Handler;
use GBProd\DoctrineSpecification\Registry;
use GBProd\DoctrineSpecification\ExpressionBuilder\AndXBuilder;
use GBProd\DoctrineSpecification\ExpressionBuilder\Builder;
use GBProd\DoctrineSpecification\ExpressionBuilder\NotBuilder;
use GBProd\DoctrineSpecification\ExpressionBuilder\OrXBuilder;
use GBProd\Specification\AndX;
use GBProd\Specification\Not;
use GBProd\Specification\OrX;
use GBProd\Specification\Specification;

class HandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructWillRegisterBaseBuilders()
    {
        $registry = new Registry();

        new Handler($registry);

        $spec1 = $this->getMock(Specification::class);
        $spec2 = $this->getMock(Specification::class);

        $this->assertInstanceOf(
            AndXBuilder::class,
            $registry->getBuilder(new AndX($spec1, $spec2))
        );

        $this->assertInstanceOf(
            OrXBuilder::class,
            $registry->getBuilder(new OrX($spec1, $spec2))
        );

        $this->assertInstanceOf(
            NotBuilder::class,
            $registry->getBuilder(new Not($spec1))
        );
    }

    public function testRegisterBuilderAddBuilderInRegistry()
    {
        $registry = new Registry();

        $handler = new Handler($registry);

        $builder = $this->getMock(Builder::class);
        $spec = $this->getMock(Specification::class);

        $handler->registerBuilder(get_class($spec), $builder);

        $this->assertEquals(
            $builder,
            $registry->getBuilder($spec)
        );
    }

    public function testHandle()
    {
        $handler = new Handler(new Registry());

        $builder = $this->getMock(Builder::class);
        $spec = $this->getMock(Specification::class);
        $handler->registerBuilder(get_class($spec), $builder);

        $buildedExpr = $this->getMockForAbstractClass(Base::class);

        $result = array(new \stdClass(), new \stdClass(), new \stdClass());
        $query = $this->createQueryWithResult($result);

        $qb = $this->createQueryBuilderBuildingQuery($query, $buildedExpr, $spec);

        $builder
            ->expects($this->once())
            ->method('build')
            ->with($spec, $qb)
            ->willReturn($buildedExpr)
        ;

        $this->assertEquals(
            $result,
            $handler->handle($spec, $qb)
        );
    }

    private function createQueryWithResult($result)
    {
        $query = $this
            ->getMockBuilder(AbstractQuery::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $query
            ->expects($this->once())
            ->method('getResult')
            ->willReturn($result)
        ;

        return $query;
    }

    private function createQueryBuilderBuildingQuery($query, $buildedExpr, $spec)
    {
        $qb = $this
            ->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $qb
            ->expects($this->at(0))
            ->method('where')
            ->with($buildedExpr)
        ;

        $qb
            ->expects($this->at(1))
            ->method('getQuery')
            ->willReturn($query)
        ;

        return $qb;
    }
}
