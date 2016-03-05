<?php

namespace Tests\GBProd\DoctrineSpecification;

use GBProd\DoctrineSpecification\ExpressionBuilder\AndXBuilder;
use GBProd\DoctrineSpecification\Registry;

class AndXBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        new AndXBuilder(new Registry());
    }
}
