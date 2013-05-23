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
    public function testSetTokenizer()
    {
        $condition = new BaseCondition();
        $this->assertInstanceOf('Sirprize\Queried\Where\BaseCondition', $condition->setTokenizer(new Tokenizer()));
    }
    
    /**
     * @expectedException Sirprize\Queried\Where\ConditionException
     */
    public function testGetTokenizer()
    {
        $condition = new BaseCondition();
        $condition->getTokenizer();
    }
    
    public function testConstructorValues()
    {
        $values = array('artist' => 'rebolledo', 'label' => 'comeme');
        $condition = new BaseCondition($values);
        $this->assertSame('rebolledo', $condition->getValue('artist'));
        $this->assertSame('comeme', $condition->getValue('label'));
        $this->assertArrayHasKey('artist', $condition->getValues());
        $this->assertArrayHasKey('label', $condition->getValues());
    }
    
    public function testSetValues()
    {
        $condition = new BaseCondition();
        $condition->setValues(array('artist' => 'rebolledo', 'label' => 'comeme'));
        $this->assertSame('rebolledo', $condition->getValue('artist'));
        $this->assertSame('comeme', $condition->getValue('label'));
        $this->assertArrayHasKey('artist', $condition->getValues());
        $this->assertArrayHasKey('label', $condition->getValues());
    }
    
    public function testAddValue()
    {
        $condition = new BaseCondition();
        $condition->addValue('artist', 'rebolledo');
        $this->assertSame('rebolledo', $condition->getValue('artist'));
        $this->assertArrayHasKey('artist', $condition->getValues());
    }
    
    /**
     * @expectedException Sirprize\Queried\Where\ConditionException
     */
    public function testNonExistingValue()
    {
        $condition = new BaseCondition();
        $condition->getValue('asdfasdf');
    }
    
    public function testSetAliases()
    {
        $condition = new BaseCondition();
        $condition->setAliases(array('artist' => 'artist', 'label' => 'label'));
        $this->assertSame('artist', $condition->getAlias('artist'));
        $this->assertSame('label', $condition->getAlias('label'));
        $this->assertArrayHasKey('artist', $condition->getAliases());
        $this->assertArrayHasKey('label', $condition->getAliases());
    }
    
    public function testAddAlias()
    {
        $condition = new BaseCondition();
        $condition->addAlias('artist', 'artist');
        $this->assertSame('artist', $condition->getAlias('artist'));
        $this->assertArrayHasKey('artist', $condition->getAliases());
    }
    
    /**
     * @expectedException Sirprize\Queried\Where\ConditionException
     */
    public function testNonExistingAlias()
    {
        $condition = new BaseCondition();
        $condition->getAlias('asdfasdf');
    }
    
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