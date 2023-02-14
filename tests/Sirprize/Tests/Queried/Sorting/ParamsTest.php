<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\Sorting;

use Sirprize\Queried\Sorting\Params;
use PHPUnit\Framework\TestCase;

class ParamsTest extends TestCase
{
    public function testSet()
    {
        $params = new Params();
        $params->setRule('title');
        $params->setDirection('asc');

        $this->assertEquals('title', $params->getRule());
        $this->assertEquals('asc', $params->getDirection());
    }
}