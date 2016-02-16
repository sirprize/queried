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
        
        $this->rules->newRule('title')
            ->addAscColumn('release.title', 'asc')
            ->addDescColumn('release.title', 'desc')
            ->setDefaultDirection('asc')
        ;

        $this->rules->newRule('date')
            ->addAscColumn('release.date', 'asc')
            ->addDescColumn('release.date', 'desc')
            ->setDefaultDirection('desc')
        ;
    }

    public function tearDown()
    {
        $this->rules = null;
    }

    public function testParamsToRules()
    {
        $params = new Params('title', 'asc');
        $sorting = new Sorting();
        $sorting->setRules($this->rules);
        $sorting->setParams($params);

        $columns = $sorting->getColumns();
        $this->assertArrayHasKey('release.title', $columns);
        $this->assertSame(1, count($columns));
    }

    public function testNoParamsWithDefault()
    {
        $defaults = new Params('title', 'asc');
        $sorting = new Sorting();
        $sorting->setRules($this->rules);
        $sorting->setDefaults($defaults);

        $columns = $sorting->getColumns();
        $this->assertArrayHasKey('release.title', $columns);
    }

    public function testNoParamsNoDefault()
    {
        $params = new Params();
        $sorting = new Sorting();
        $sorting->setRules($this->rules);
        $sorting->setParams($params);

        $columns = $sorting->getColumns();
        $this->assertSame(0, count($columns));
    }
}