<?php

namespace Tests\GBProd\DoctrineSpecification\QueryModifier;

use Doctrine\ORM\Query\Expr;
use GBProd\DoctrineSpecification\QueryModifier\AndXModifier;
use GBProd\Specification\AndX;

/**
 * Tests for AndXModifier
 *
 * @author gbprod <contact@gb-prod.fr>
 */
class AndXModifierTest extends \PHPUnit_Framework_TestCase
{
    public function testModifierCreatesAndQuery()
    {
        $modifier = new AndXModifier(
            $this->getMock('GBProd\DoctrineSpecification\Handler')
        );

        $qb = $this->getQueryBuilderMock(new Expr());

        $spec = new AndX(
            $this->getMock('GBProd\Specification\Specification'),
            $this->getMock('GBProd\Specification\Specification')
        );

        $qb = $modifier->modify($spec, $qb);

        $this->assertInstanceOf(
            'Doctrine\ORM\Query\Expr\Andx',
            $qb
        );
    }

    private function getQueryBuilderMock(Expr $expr)
    {
        $qb = $this
            ->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $qb
            ->expects($this->any())
            ->method('expr')
            ->willReturn($expr)
        ;

        return $qb;
    }

    public function testModifyBuildAndxWithHandledParts()
    {
        #todo make this test better pliz
        $firstPart  = $this->getMock('GBProd\Specification\Specification');
        $secondPart = $this->getMock('GBProd\Specification\Specification');
        $spec = new AndX($firstPart, $secondPart);

        $firstHandledQb = $this->getQueryBuilderMock(new Expr());
        $secondHandledQb = $this->getQueryBuilderMock(new Expr());

        $handler = $this->getMock('GBProd\DoctrineSpecification\Handler');
        $handler
            ->expects($this->exactly(2))
            ->method('handle')
            ->will(
                $this->returnCallback(
                    function($part) use ($firstPart, $firstHandledQb, $secondPart, $secondHandledQb) {
                        if ($part == $firstPart) {
                            return $firstHandledQb;
                        }

                        if ($part == $secondPart) {
                            return $secondHandledQb;
                        }

                        return null;
                    }
                )
            )
        ;
        $modifier = new AndXModifier($handler);

        $expr = $this->getMock('Doctrine\ORM\Query\Expr');
        $expr
            ->expects($this->once())
            ->method('andx')
            ->with($firstHandledQb, $secondHandledQb)
        ;
        $qb = $this->getQueryBuilderMock($expr);

        $modifier->modify($spec, $qb);
    }
}