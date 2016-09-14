<?php

namespace Tests\GBProd\DoctrineSpecification;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Base;
use GBProd\DoctrineSpecification\Handler;
use GBProd\DoctrineSpecification\Registry;
use GBProd\DoctrineSpecification\QueryFactory\AndXFactory;
use GBProd\DoctrineSpecification\QueryFactory\Factory;
use GBProd\DoctrineSpecification\QueryFactory\NotFactory;
use GBProd\DoctrineSpecification\QueryFactory\OrXFactory;
use GBProd\Specification\AndX;
use GBProd\Specification\Not;
use GBProd\Specification\OrX;
use GBProd\Specification\Specification;

class HandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructWillRegisterBaseFactorys()
    {
        $registry = new Registry();

        new Handler($registry);

        $spec1 = $this->getMock(Specification::class);
        $spec2 = $this->getMock(Specification::class);

        $this->assertInstanceOf(
            AndXFactory::class,
            $registry->getFactory(new AndX($spec1, $spec2))
        );

        $this->assertInstanceOf(
            OrXFactory::class,
            $registry->getFactory(new OrX($spec1, $spec2))
        );

        $this->assertInstanceOf(
            NotFactory::class,
            $registry->getFactory(new Not($spec1))
        );
    }

    public function testRegisterFactoryAddFactoryInRegistry()
    {
        $registry = new Registry();

        $handler = new Handler($registry);

        $factory = $this->getMock(Factory::class);
        $spec = $this->getMock(Specification::class);

        $handler->registerFactory(get_class($spec), $factory);

        $this->assertEquals(
            $factory,
            $registry->getFactory($spec)
        );
    }

    public function testHandle()
    {
        $handler = new Handler(new Registry());

        $factory = $this->getMock(Factory::class);
        $spec = $this->getMock(Specification::class);
        $handler->registerFactory(get_class($spec), $factory);

        $buildedExpr = $this->getMockForAbstractClass(Base::class);

        $result = array(new \stdClass(), new \stdClass(), new \stdClass());
        $query = $this->createQueryWithResult($result);

        $qb = $this->createQueryBuilderBuildingQuery($query, $buildedExpr, $spec);

        $factory
            ->expects($this->once())
            ->method('create')
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
