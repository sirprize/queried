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
    public function testConstructorArgs()
    {
        $params = new Params(array('title' => 'asc'), array('date' => 'desc'));
        $this->assertArrayHasKey('title', $params->get());
        $this->assertArrayHasKey('date', $params->getDefaults());
    }
    
    public function testArgsSetters()
    {
        $params = new Params();
        $params->set(array('title' => 'asc'));
        $params->setDefaults(array('date' => 'desc'));
        $this->assertArrayHasKey('title', $params->get());
        $this->assertArrayHasKey('date', $params->getDefaults());
    }
    
    public function testArgSetters()
    {
        $params = new Params();
        $params->add('title', 'asc');
        $params->addDefault('date', 'desc');
        $this->assertArrayHasKey('title', $params->get());
        $this->assertArrayHasKey('date', $params->getDefaults());
    }
}