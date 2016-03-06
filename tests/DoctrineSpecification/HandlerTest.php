<?php

namespace Tests\GBProd\DoctrineSpecification;

use GBProd\DoctrineSpecification\Handler;
use GBProd\DoctrineSpecification\Registry;
use GBProd\DoctrineSpecification\ExpressionBuilder\AndXBuilder;
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
}
