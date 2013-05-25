<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried;

use Sirprize\Queried\BaseQuery;
use Sirprize\Queried\Condition\BaseCondition;

class BaseQueryTest extends \PHPUnit_Framework_TestCase
{
    protected $query = null;
    
    public function setup()
    {
        $this->query = new BaseQuery();
    }
    
    public function tearDown()
    {
        $this->query = null;
    }
    
    public function testAddCondition()
    {
        $this->assertInstanceOf('Sirprize\Queried\BaseQuery', $this->query->registerCondition('someCondition', new BaseCondition()));
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
        $this->assertInstanceOf('Sirprize\Queried\BaseQuery', $this->query->setRange($range));
    }

    public function testFull()
    {
        $publishedCondition = new BaseCondition();
        $publishedCondition->setClause("(release.date <= CURRENT_DATE() AND release.published = 1)");

        $physicalCondition = new BaseCondition();
        $physicalCondition->setClause("(release.format = 'LP' OR release.format = 'CD'");

        $digitalCondition = new BaseCondition();
        $digitalCondition->setClause("(release.format = 'MP3' OR release.format = 'WAV'");

        $query = new BaseQuery();

        $query
            ->registerCondition('published', $publishedCondition)
            ->registerCondition('physical', $physicalCondition)
            ->registerCondition('digital', $physicalCondition)
        ;

        $query->activateCondition('published');

        if (true) {
            $query->activateCondition('digital');
        }
        else {
            $query->activateCondition('physical');
        }

        $clauses = array();

        foreach($query->getActiveConditions() as $condition)
        {
            $clauses[] = $condition->getClause();
        }

        $statement = 'SELECT * FROM release WHERE ' . implode(' AND ', $clauses);
        #die($statement);
    }
}