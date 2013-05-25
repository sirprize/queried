<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\Sorting;

use Sirprize\Queried\Sorting\Rules;

class RulesTest extends \PHPUnit_Framework_TestCase
{
    protected $rules = null;
    
    public function setup()
    {
        $this->rules = new Rules();
        
        $this->rules->newRule('title')
            ->addAscColumn('release.title', 'asc')
            ->addDescColumn('release.title', 'desc')
            ->setDefaultOrder('asc')
        ;
        
        $this->rules->newRule('date')
            ->addAscColumn('release.date', 'asc')
            ->addDescColumn('release.date', 'desc')
            ->setDefaultOrder('desc')
        ;
    }
    
    public function tearDown()
    {
        $this->rules = null;
    }
    
    public function testRuleSetters()
    {
        $this->assertArrayHasKey('release.title', $this->rules->findColumns('title', 'asc'));
        $this->assertArrayHasKey('release.date', $this->rules->findColumns('date', 'asc'));
    }
    
    public function testDefaultOrder()
    {
        $expressions = $this->rules->findColumns('title', 'asdfasdfasdf');
        $this->assertSame('asc', $expressions['release.title']);
        
        $expressions = $this->rules->findColumns('date', 'asdfasdfasdf');
        $this->assertSame('desc', $expressions['release.date']);
    }
}