<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Tests\Queried;

use Sirprize\Queried\BaseQueryConfigurator;
use Sirprize\Queried\Condition\BaseCondition;

class BaseQueryConfiguratorTest extends \PHPUnit_Framework_TestCase
{
    protected $queryConfigurator = null;

    public function setup()
    {
        $this->queryConfigurator = new BaseQueryConfigurator();
    }

    public function tearDown()
    {
        $this->queryConfigurator = null;
    }

    public function testAddCondition()
    {
        $this->assertInstanceOf('Sirprize\Queried\BaseQueryConfigurator', $this->queryConfigurator->registerCondition('someCondition', new BaseCondition()));
        $this->assertTrue($this->queryConfigurator->hasCondition('someCondition'));
        $this->assertFalse($this->queryConfigurator->hasActiveCondition('someCondition'));
    }

    public function testActivateCondition()
    {
        $this->queryConfigurator->registerCondition('someCondition', new BaseCondition());
        $this->queryConfigurator->activateCondition('someCondition');
        $this->assertTrue($this->queryConfigurator->hasActiveCondition('someCondition'));
    }

    /**
     * @expectedException Sirprize\Queried\Exception\InvalidArgumentException
     */
    public function testActivateNonExistingCondition()
    {
        $this->queryConfigurator->activateCondition('asdfdsf');
    }
}