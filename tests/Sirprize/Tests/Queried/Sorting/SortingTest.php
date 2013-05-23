<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\Sorting;

use Sirprize\Queried\Sorting\Rules;
use Sirprize\Queried\Sorting\Params;
use Sirprize\Queried\Sorting\Sorting;

class SortingTest extends \PHPUnit_Framework_TestCase
{
    protected $rules = null;
    
    public function setup()
    {
        $this->rules = new Rules();
        
        $title = $this->rules->newRule()
            ->addAscColumn('release.title', 'asc')
            ->addDescColumn('release.title', 'desc')
            ->setDefaultOrder('asc')
        ;
        
        $date = $this->rules->newRule()
            ->addAscColumn('release.date', 'asc')
            ->addDescColumn('release.date', 'desc')
            ->setDefaultOrder('desc')
        ;
        
        $this->rules
            ->addRule('title', $title)
            ->addRule('date', $date)
        ;
    }
    
    public function tearDown()
    {
        $this->rules = null;
    }
    
    public function testParamsToRules()
    {
        $params = new Params();
        $params->add('title', 'asc');
        $sorting = new Sorting($this->rules, $params);
        $expressions = $sorting->getColumns();
        
        $this->assertArrayHasKey('release.title', $expressions);
        $this->assertSame(1, count($expressions));
    }
    
    public function testNestedSortingParams()
    {
        $params = new Params();
        $params->add('title', 'asc');
        $params->add('date', 'asc');
        $sorting = new Sorting($this->rules, $params);
        $expressions = $sorting->getColumns();
        
        $this->assertArrayHasKey('release.title', $expressions);
        $this->assertArrayHasKey('release.date', $expressions);
    }
    
    public function testNoParamsWithDefault()
    {
        $params = new Params();
        $params->addDefault('title', 'asc');
        $sorting = new Sorting($this->rules, $params);
        $expressions = $sorting->getColumns();
        
        $this->assertArrayHasKey('release.title', $expressions);
    }
    
    public function testNoParamsNoDefault()
    {
        $params = new Params();
        $sorting = new Sorting($this->rules, $params);
        $expressions = $sorting->getColumns();
        
        $this->assertSame(0, count($expressions));
    }
}