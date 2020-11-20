<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Tests\Queried;

use Sirprize\Queried\Condition\Registry;
use Sirprize\Queried\Condition\BaseCondition;

class RegistryTest extends \PHPUnit_Framework_TestCase
{
    protected $registry = null;

    public function setup()
    {
        $this->registry = new Registry();
    }

    public function tearDown()
    {
        $this->registry = null;
    }

    public function testAddCondition()
    {
        $this->assertInstanceOf('Sirprize\Queried\Condition\Registry', $this->registry->registerCondition('someCondition', new BaseCondition()));
        $this->assertTrue($this->registry->hasCondition('someCondition'));
        $this->assertFalse($this->registry->hasActiveCondition('someCondition'));
    }

    public function testActivateCondition()
    {
        $this->registry->registerCondition('someCondition', new BaseCondition());
        $this->registry->activateCondition('someCondition');
        $this->assertTrue($this->registry->hasActiveCondition('someCondition'));
    }

    /**
     * @expectedException Sirprize\Queried\Exception\InvalidArgumentException
     */
    public function testActivateNonExistingCondition()
    {
        $this->registry->activateCondition('asdfdsf');
    }
}