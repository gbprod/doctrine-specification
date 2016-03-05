<?php

namespace Tests\GBProd\DoctrineSpecification;

use GBProd\DoctrineSpecification\Handler;
use GBProd\Specification\Specification;

/**
 * Tests for DoctrineSpecification Handler
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class HandlerTest extends \PHPUnit_Framework_TestCase  implements Specification
{
    public function isSatisfiedBy($candidate)
    {
        return true;
    }

    public function testConstruction()
    {
        new Handler();
    }

    public function testHandlingNotRegistredSpecThrowsException()
    {
        $handler = new Handler();

        $this->setExpectedException('\OutOfRangeException');

        $handler->handle(
            $this,
            $this->getQueryBuilderMock()
        );
    }

    private function getQueryBuilderMock()
    {
        return $this
            ->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    public function testHandlingRegistredSpecExecuteFilter()
    {
        $qb = $this->getQueryBuilderMock();

        $modifier = $this->getMock('GBProd\DoctrineSpecification\QueryModifier\Modifier');
        $modifier
            ->expects($this->once())
            ->method('modify')
            ->with($this, $qb)
        ;

        $handler = new Handler();
        $handler->registerModifier(HandlerTest::class, $modifier);

        $handler->handle($this, $qb);
    }

}
