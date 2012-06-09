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
        
        $title = $this->rules->newRule()
            ->addAscExpression('release.title', 'asc')
            ->addDescExpression('release.title', 'desc')
            ->setDefaultOrder('asc')
        ;
        
        $date = $this->rules->newRule()
            ->addAscExpression('release.date', 'asc')
            ->addDescExpression('release.date', 'desc')
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
    
    public function testRuleSetters()
    {
        $this->assertArrayHasKey('release.title', $this->rules->findExpressions('title', 'asc'));
        $this->assertArrayHasKey('release.date', $this->rules->findExpressions('date', 'asc'));
    }
    
    public function testDefaultOrder()
    {
        $expressions = $this->rules->findExpressions('title', 'asdfasdfasdf');
        $this->assertSame('asc', $expressions['release.title']);
        
        $expressions = $this->rules->findExpressions('date', 'asdfasdfasdf');
        $this->assertSame('desc', $expressions['release.date']);
    }
}