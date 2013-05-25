<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\Sorting;

use Sirprize\Queried\Sorting\Rules;
use Sirprize\Queried\Sorting\Input;
use Sirprize\Queried\Sorting\Sorting;

class SortingTest extends \PHPUnit_Framework_TestCase
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
    
    public function testInputToRules()
    {
        $input = new Input();
        $input->add('title', 'asc');
        $sorting = new Sorting($this->rules, $input);
        $expressions = $sorting->getColumns();
        
        $this->assertArrayHasKey('release.title', $expressions);
        $this->assertSame(1, count($expressions));
    }
    
    public function testNestedSortingInput()
    {
        $input = new Input();
        $input->add('title', 'asc');
        $input->add('date', 'asc');
        $sorting = new Sorting($this->rules, $input);
        $expressions = $sorting->getColumns();
        
        $this->assertArrayHasKey('release.title', $expressions);
        $this->assertArrayHasKey('release.date', $expressions);
    }
    
    public function testNoInputWithDefault()
    {
        $input = new Input();
        $input->addDefault('title', 'asc');
        $sorting = new Sorting($this->rules, $input);
        $expressions = $sorting->getColumns();
        
        $this->assertArrayHasKey('release.title', $expressions);
    }
    
    public function testNoInputNoDefault()
    {
        $input = new Input();
        $sorting = new Sorting($this->rules, $input);
        $expressions = $sorting->getColumns();
        
        $this->assertSame(0, count($expressions));
    }
}