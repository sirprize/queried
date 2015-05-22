<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\Doctrine\ORM;

use Sirprize\Queried\Condition\Tokenizer;
use Sirprize\Queried\Doctrine\ORM\SimpleConditionFactory;

class ConditionBuilderTest extends \PHPUnit_Framework_TestCase
{   
    public function testLikeCondition()
    {
        $conditionFactory = new SimpleConditionFactory(new Tokenizer());
        $conditionClosure = $conditionFactory->like('artist', 'release');
        $condition = $conditionClosure(array('value' => 'Rebolledo'));
        
        $this->assertInstanceOf('Sirprize\Queried\Condition\BaseCondition', $condition);
        $this->assertSame('release.artist LIKE :token0', $condition->getClause());
    }

    public function testIsCondition()
    {
        $conditionFactory = new SimpleConditionFactory(new Tokenizer());
        $conditionClosure = $conditionFactory->is('artist', 'release');
        $condition = $conditionClosure(array('value' => 'Rebolledo'));
        
        $this->assertInstanceOf('Sirprize\Queried\Condition\BaseCondition', $condition);
        $this->assertSame('release.artist = :token0', $condition->getClause());
    }

    public function testIsNotCondition()
    {
        $conditionFactory = new SimpleConditionFactory(new Tokenizer());
        $conditionClosure = $conditionFactory->not('artist', 'release');
        $condition = $conditionClosure(array('value' => 'Rebolledo'));
        
        $this->assertInstanceOf('Sirprize\Queried\Condition\BaseCondition', $condition);
        $this->assertSame('release.artist != :token0', $condition->getClause());
    }
}