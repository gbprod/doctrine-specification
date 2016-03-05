<?php

namespace Tests\GBProd\DoctrineSpecification;

use GBProd\DoctrineSpecification\ExpressionBuilder\NotBuilder;
use GBProd\DoctrineSpecification\Registry;

class NotBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        new NotBuilder(new Registry());
    }
}
