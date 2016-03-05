<?php

namespace Tests\GBProd\DoctrineSpecification;

use GBProd\DoctrineSpecification\ExpressionBuilder\OrXBuilder;
use GBProd\DoctrineSpecification\Registry;

class OrXBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        new OrXBuilder(new Registry());
    }
}
