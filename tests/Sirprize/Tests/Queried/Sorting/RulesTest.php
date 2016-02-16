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
        $this->rules->newRule('title');
    }

    public function tearDown()
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