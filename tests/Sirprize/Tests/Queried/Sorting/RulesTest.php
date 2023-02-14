<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Tests\Queried\Sorting;

use Sirprize\Queried\Sorting\Rules;
use PHPUnit\Framework\TestCase;

class RulesTest extends TestCase
{
    protected $rules = null;

    protected function setup(): void
    {
        $this->rules = new Rules();
        $this->rules->newRule('title');
    }

    protected function tearDown(): void
    {
        $this->rules = null;
    }

    public function testRuleSetters()
    {
        $this->assertTrue($this->rules->hasRule('title'));
    }

    public function testDefaultDirection()
    {
        $rule = $this->rules->getRule('title');
        $this->assertInstanceOf('Sirprize\Queried\Sorting\Rule', $rule);
    }
}