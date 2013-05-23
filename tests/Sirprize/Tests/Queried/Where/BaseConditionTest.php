<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\Where;

use Sirprize\Queried\Where\BaseCondition;
use Sirprize\Queried\Where\Tokenizer;

class BaseConditionTest extends \PHPUnit_Framework_TestCase
{
    public function testSetGetCondition()
    {
        $condition = new BaseCondition();
        $this->assertInstanceOf('Sirprize\Queried\Where\BaseCondition', $condition->setClause('asdfsdfsdf'));
        $this->assertSame('asdfsdfsdf', $condition->getClause());
    }
    
    public function testParams()
    {
        $condition = new BaseCondition();
        $this->assertInstanceOf('Sirprize\Queried\Where\BaseCondition', $condition->setParams(array('artist' => 'rebolledo', 'label' => 'comeme')));
        $this->assertInstanceOf('Sirprize\Queried\Where\BaseCondition', $condition->addParam('type', 'exclusive'));
        $this->assertArrayHasKey('artist', $condition->getParams());
        $this->assertArrayHasKey('type', $condition->getParams());
    }
    
    public function testTypes()
    {
        $condition = new BaseCondition();
        $this->assertInstanceOf('Sirprize\Queried\Where\BaseCondition', $condition->setTypes(array('creation_date' => 'DateTime')));
        $this->assertInstanceOf('Sirprize\Queried\Where\BaseCondition', $condition->addType('modification_date', 'DateTime'));
        $this->assertArrayHasKey('creation_date', $condition->getTypes());
        $this->assertArrayHasKey('modification_date', $condition->getTypes());
    }
}