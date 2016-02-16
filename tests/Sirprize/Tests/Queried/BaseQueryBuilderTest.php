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
}