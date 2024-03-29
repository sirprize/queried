<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\Sorting;

use Sirprize\Queried\Sorting\Rule;
use PHPUnit\Framework\TestCase;

class RuleTest extends TestCase
{
    public function testSetters()
    {
        $rule = new Rule();
        
        $rule
            ->addAscColumn('release.title', 'asc')
            ->addAscColumn('release.date', 'desc')
            ->addDescColumn('release.title', 'desc')
            ->addDescColumn('release.date', 'desc')
            ->setDefaultDirection('asc')
        ;
        
        $this->assertArrayHasKey('release.title', $rule->getAscColumns());
        $this->assertArrayHasKey('release.date', $rule->getAscColumns());
        $this->assertArrayHasKey('release.title', $rule->getDescColumns());
        $this->assertArrayHasKey('release.date', $rule->getDescColumns());
        $this->assertSame('asc', $rule->getDefaultDirection());
    }
}