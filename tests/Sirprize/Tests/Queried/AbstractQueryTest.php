<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried;

use Sirprize\Queried\Where\BaseCondition;

class AbstractQueryTest extends \PHPUnit_Framework_TestCase
{
    protected $query = null;
    
    public function setup()
    {
        $this->query = $this->getMockForAbstractClass('Sirprize\Queried\AbstractQuery');
    }
    
    public function tearDown()
    {
        $this->query = null;
    }
    
    public function testAddCondition()
    {
        $this->assertInstanceOf('Sirprize\Queried\AbstractQuery', $this->query->registerCondition('someCondition', new BaseCondition()));
        $this->assertTrue($this->query->hasCondition('someCondition'));
        $this->assertFalse($this->query->hasActiveCondition('someCondition'));
    }

    public function testActivateCondition()
    {
        $this->query->registerCondition('someCondition', new BaseCondition());
        $this->query->activateCondition('someCondition');
        $this->assertTrue($this->query->hasActiveCondition('someCondition'));
    }

    /**
     * @expectedException Sirprize\Queried\QueryException
     */
    public function testActivateNonExistingCondition()
    {
        $this->query->activateCondition('asdfdsf');
    }
    
    public function testSetRange()
    {
        $range = $this->getMock('Sirprize\Paginate\Range\RangeInterface');
        $this->assertInstanceOf('Sirprize\Queried\AbstractQuery', $this->query->setRange($range));
    }
}