<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried;

use Sirprize\Queried\BaseClause;

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
    
    /**
     * @expectedException Sirprize\Queried\QueryException
     */
    public function testActivateNonExistingClause()
    {
        $this->query->activateClause('asdfdsf');
    }
    
    public function zzzztestAddClause()
    {
        $this->assertInstanceOf('Sirprize\Queried\AbstractQuery', $this->query->registerClause('someClause', new BaseClause()));
        $this->assertTrue($this->query->hasClause('someClause'));
        #$this->assertTrue($this->query->isActive('someClause'));
    }
    
    public function testSetRange()
    {
        $range = $this->getMock('Sirprize\Paginate\Range\RangeInterface');
        $this->assertInstanceOf('Sirprize\Queried\AbstractQuery', $this->query->setRange($range));
    }
    
    /**
     * @expectedException Sirprize\Queried\QueryException
     */
    public function testGetRange()
    {
        $this->query->getRange();
    }
    
    public function testGetTokenizer()
    {
        $this->assertInstanceOf('Sirprize\Queried\Tokenizer', $this->query->getTokenizer());
    }
    
    public function testGetSortingRules()
    {
        $this->assertInstanceOf('Sirprize\Queried\Sorting\Rules', $this->query->getSortingRules());
    }
}