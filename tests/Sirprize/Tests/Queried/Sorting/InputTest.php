<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\Sorting;

use Sirprize\Queried\Sorting\Input;

class InputTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorArgs()
    {
        $input = new Input(array('title' => 'asc'), array('date' => 'desc'));
        $this->assertArrayHasKey('title', $input->get());
        $this->assertArrayHasKey('date', $input->getDefaults());
    }
    
    public function testSet()
    {
        $input = new Input();
        $input->set(array('title' => 'asc'));
        $input->setDefaults(array('date' => 'desc'));
        $this->assertArrayHasKey('title', $input->get());
        $this->assertArrayHasKey('date', $input->getDefaults());
    }
    
    public function testAdd()
    {
        $input = new Input();
        $input->add('title', 'asc');
        $input->addDefault('date', 'desc');
        $this->assertArrayHasKey('title', $input->get());
        $this->assertArrayHasKey('date', $input->getDefaults());
    }
}