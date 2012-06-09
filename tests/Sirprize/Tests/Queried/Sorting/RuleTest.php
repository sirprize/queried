<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\Sorting;

use Sirprize\Queried\Sorting\Rule;

class RuleTest extends \PHPUnit_Framework_TestCase
{
    public function testSetters()
    {
        $rule = new Rule();
        
        $rule
            ->addAscExpression('release.title', 'asc')
            ->addAscExpression('release.date', 'desc')
            ->addDescExpression('release.title', 'desc')
            ->addDescExpression('release.date', 'desc')
            ->setDefaultOrder('asc')
        ;
        
        $this->assertArrayHasKey('release.title', $rule->getAscExpressions());
        $this->assertArrayHasKey('release.date', $rule->getAscExpressions());
        $this->assertArrayHasKey('release.title', $rule->getDescExpressions());
        $this->assertArrayHasKey('release.date', $rule->getDescExpressions());
        $this->assertSame('asc', $rule->getDefaultOrder());
    }
}