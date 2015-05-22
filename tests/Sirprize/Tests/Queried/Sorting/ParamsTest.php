<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\Sorting;

use Sirprize\Queried\Sorting\Params;

class ParamsTest extends \PHPUnit_Framework_TestCase
{
    public function testSet()
    {
        $params = new Params();
        $params->set(array('title' => 'asc'));
        $this->assertArrayHasKey('title', $params->get());
    }

    public function testAdd()
    {
        $params = new Params();
        $params->add('title', 'asc');
        $this->assertArrayHasKey('title', $params->get());
    }
}