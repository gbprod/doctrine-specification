<?php

namespace Tests\GBProd\DoctrineSpecification;

use GBProd\DoctrineSpecification\Handler;
use GBProd\DoctrineSpecification\Registry;

class HandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        new Handler(new Registry());
    }
}
