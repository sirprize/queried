<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried;

use Sirprize\Queried\BaseClause;
use Sirprize\Queried\Tokenizer;

class BaseClauseTest extends \PHPUnit_Framework_TestCase
{
    public function testSetTokenizer()
    {
        $clause = new BaseClause();
        $this->assertInstanceOf('Sirprize\Queried\BaseClause', $clause->setTokenizer(new Tokenizer()));
    }
    
    /**
     * @expectedException Sirprize\Queried\QueryException
     */
    public function testGetTokenizer()
    {
        $clause = new BaseClause();
        $clause->getTokenizer();
    }
    
    public function testConstructorArgs()
    {
        $args = array('artist' => 'rebolledo', 'label' => 'comeme');
        $clause = new BaseClause($args);
        $this->assertSame('rebolledo', $clause->getArg('artist'));
        $this->assertSame('comeme', $clause->getArg('label'));
        $this->assertArrayHasKey('artist', $clause->getArgs());
        $this->assertArrayHasKey('label', $clause->getArgs());
    }
    
    public function testSetArgs()
    {
        $clause = new BaseClause();
        $clause->setArgs(array('artist' => 'rebolledo', 'label' => 'comeme'));
        $this->assertSame('rebolledo', $clause->getArg('artist'));
        $this->assertSame('comeme', $clause->getArg('label'));
        $this->assertArrayHasKey('artist', $clause->getArgs());
        $this->assertArrayHasKey('label', $clause->getArgs());
    }
    
    public function testAddArg()
    {
        $clause = new BaseClause();
        $clause->addArg('artist', 'rebolledo');
        $this->assertSame('rebolledo', $clause->getArg('artist'));
        $this->assertArrayHasKey('artist', $clause->getArgs());
    }
    
    /**
     * @expectedException Sirprize\Queried\QueryException
     */
    public function testNonExistingArg()
    {
        $clause = new BaseClause();
        $clause->getArg('asdfasdf');
    }
    
    public function testSetAliases()
    {
        $clause = new BaseClause();
        $clause->setAliases(array('artist' => 'artist', 'label' => 'label'));
        $this->assertSame('artist', $clause->getAlias('artist'));
        $this->assertSame('label', $clause->getAlias('label'));
        $this->assertArrayHasKey('artist', $clause->getAliases());
        $this->assertArrayHasKey('label', $clause->getAliases());
    }
    
    public function testAddAlias()
    {
        $clause = new BaseClause();
        $clause->addAlias('artist', 'artist');
        $this->assertSame('artist', $clause->getAlias('artist'));
        $this->assertArrayHasKey('artist', $clause->getAliases());
    }
    
    /**
     * @expectedException Sirprize\Queried\QueryException
     */
    public function testNonExistingAlias()
    {
        $clause = new BaseClause();
        $clause->getAlias('asdfasdf');
    }
    
    public function testSetGetClause()
    {
        $clause = new BaseClause();
        $this->assertInstanceOf('Sirprize\Queried\BaseClause', $clause->setClause('asdfsdfsdf'));
        $this->assertSame('asdfsdfsdf', $clause->getClause());
    }
    
    public function testParams()
    {
        $clause = new BaseClause();
        $this->assertInstanceOf('Sirprize\Queried\BaseClause', $clause->setParams(array('artist' => 'rebolledo', 'label' => 'comeme')));
        $this->assertInstanceOf('Sirprize\Queried\BaseClause', $clause->addParam('type', 'exclusive'));
        $this->assertArrayHasKey('artist', $clause->getParams());
        $this->assertArrayHasKey('type', $clause->getParams());
    }
    
    public function testTypes()
    {
        $clause = new BaseClause();
        $this->assertInstanceOf('Sirprize\Queried\BaseClause', $clause->setTypes(array('creation_date' => 'DateTime')));
        $this->assertInstanceOf('Sirprize\Queried\BaseClause', $clause->addType('modification_date', 'DateTime'));
        $this->assertArrayHasKey('creation_date', $clause->getTypes());
        $this->assertArrayHasKey('modification_date', $clause->getTypes());
    }
}