<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\Condition;

use Sirprize\Queried\Condition\BaseCondition;
use Sirprize\Queried\Condition\Tokenizer;
use PHPUnit\Framework\TestCase;

class BaseConditionTest extends TestCase
{
    public function testSetGetCondition()
    {
        $condition = new BaseCondition();
        $this->assertInstanceOf('Sirprize\Queried\Condition\BaseCondition', $condition->setClause('asdfsdfsdf'));
        $this->assertSame('asdfsdfsdf', $condition->getClause());
    }
    
    public function testParams()
    {
        $condition = new BaseCondition();
        $this->assertInstanceOf('Sirprize\Queried\Condition\BaseCondition', $condition->setParams(array('artist' => 'rebolledo', 'label' => 'comeme')));
        $this->assertInstanceOf('Sirprize\Queried\Condition\BaseCondition', $condition->addParam('type', 'exclusive'));
        $this->assertArrayHasKey('artist', $condition->getParams());
        $this->assertArrayHasKey('type', $condition->getParams());
    }
    
    public function testTypes()
    {
        $condition = new BaseCondition();
        $this->assertInstanceOf('Sirprize\Queried\Condition\BaseCondition', $condition->setTypes(array('creation_date' => 'DateTime')));
        $this->assertInstanceOf('Sirprize\Queried\Condition\BaseCondition', $condition->addType('modification_date', 'DateTime'));
        $this->assertArrayHasKey('creation_date', $condition->getTypes());
        $this->assertArrayHasKey('modification_date', $condition->getTypes());
    }
}