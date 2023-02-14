<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Tests\Queried;

use Sirprize\Queried\Condition\Registry;
use Sirprize\Queried\Condition\BaseCondition;
use PHPUnit\Framework\TestCase;

class RegistryTest extends TestCase
{
    protected $registry = null;

    protected function setup(): void
    {
        $this->registry = new Registry();
    }

    protected function tearDown(): void
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

    public function testActivateNonExistingCondition()
    {
        $this->expectException(\Sirprize\Queried\Exception\InvalidArgumentException::class);

        $this->registry->activateCondition('asdfdsf');
    }
}