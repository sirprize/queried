<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried;

use Sirprize\Queried\BaseQueryBuilder;
use Sirprize\Queried\Condition\BaseCondition;

class BaseQueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    protected $queryBuilder = null;
    
    public function setup()
    {
        $this->queryBuilder = new BaseQueryBuilder();
    }
    
    public function tearDown()
    {
        $this->queryBuilder = null;
    }
    
    public function testAddCondition()
    {
        $this->assertInstanceOf('Sirprize\Queried\BaseQueryBuilder', $this->queryBuilder->registerCondition('someCondition', new BaseCondition()));
        $this->assertTrue($this->queryBuilder->hasCondition('someCondition'));
        $this->assertFalse($this->queryBuilder->hasActiveCondition('someCondition'));
    }

    public function testActivateCondition()
    {
        $this->queryBuilder->registerCondition('someCondition', new BaseCondition());
        $this->queryBuilder->activateCondition('someCondition');
        $this->assertTrue($this->queryBuilder->hasActiveCondition('someCondition'));
    }

    /**
     * @expectedException Sirprize\Queried\Exception\InvalidArgumentException
     */
    public function testActivateNonExistingCondition()
    {
        $this->queryBuilder->activateCondition('asdfdsf');
    }
    
    public function testSetRange()
    {
        $range = $this->getMock('Sirprize\Paginate\Range\RangeInterface');
        $this->assertInstanceOf('Sirprize\Queried\BaseQueryBuilder', $this->queryBuilder->setRange($range));
    }

    public function testFull()
    {
        $publishedCondition = new BaseCondition();
        $publishedCondition->setClause("(release.date <= CURRENT_DATE() AND release.published = 1)");

        $physicalCondition = new BaseCondition();
        $physicalCondition->setClause("(release.format = 'LP' OR release.format = 'CD'");

        $digitalCondition = new BaseCondition();
        $digitalCondition->setClause("(release.format = 'MP3' OR release.format = 'WAV'");

        $queryBuilder = new BaseQueryBuilder();

        $queryBuilder
            ->registerCondition('published', $publishedCondition)
            ->registerCondition('physical', $physicalCondition)
            ->registerCondition('digital', $physicalCondition)
        ;

        $queryBuilder->activateCondition('published');

        if (true)
        {
            $queryBuilder->activateCondition('digital');
        }
        else
        {
            $queryBuilder->activateCondition('physical');
        }

        $clauses = array();

        foreach ($queryBuilder->getActiveConditions() as $condition)
        {
            $clauses[] = $condition->getClause();
        }

        $statement = 'SELECT * FROM release WHERE ' . implode(' AND ', $clauses);
    }
}