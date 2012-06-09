<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\UseCaseQueryWithInlineAndExternalClauses;

class UseCaseQueryWithInlineAndExternalClausesTest extends \PHPUnit_Framework_TestCase
{
    public function testActivateSomeClausesByConfig()
    {
        $config = array(
            'activate' => array(
                'artist' => array('artist' => 'rebolledo')
            )
        );
        
        $query = new ReleaseQuery($config);
        $query->build();
        
        // check for activated artist clause
        $this->assertTrue($query->hasClause('artist'));
        $this->assertTrue($query->isActive('artist'));
        $this->assertInstanceOf('Sirprize\Queried\BaseClause', $query->getClause('artist'));
        $this->assertSame('release.artist LIKE :token0', $query->getClause('artist')->getClause());
        
        // check for inactive label clause
        $this->assertTrue($query->hasClause('label'));
        $this->assertFalse($query->isActive('label'));
    }
    
    public function testActivateAllClausesByConfig()
    {
        $config = array(
            'activate' => array(
                'artist' => array('artist' => 'rebolledo'),
                'label' => array('label' => 'comeme')
            )
        );
        
        $query = new ReleaseQuery($config);
        $query->build();
        
        // check for activated artist clause
        $this->assertTrue($query->hasClause('artist'));
        $this->assertTrue($query->isActive('artist'));
        $this->assertInstanceOf('Sirprize\Queried\BaseClause', $query->getClause('artist'));
        $this->assertSame('release.artist LIKE :token0', $query->getClause('artist')->getClause());
        
        // check for activated label clause
        $this->assertTrue($query->hasClause('label'));
        $this->assertTrue($query->isActive('label'));
        $this->assertInstanceOf('Sirprize\Tests\Queried\UseCaseQueryWithInlineAndExternalClauses\LabelClause', $query->getClause('label'));
        $this->assertSame('release.label = :token1', $query->getClause('label')->getClause());
    }
    
    public function testManualClauseActivation()
    {
        $query = new ReleaseQuery();
        $query->activateClause('artist', array('artist' => 'rebolledo'));
        $query->activateClause('label', array('label' => 'comeme'));
        $query->build();
        
        // check for activated artist clause
        $this->assertTrue($query->hasClause('artist'));
        $this->assertTrue($query->isActive('artist'));
        $this->assertInstanceOf('Sirprize\Queried\BaseClause', $query->getClause('artist'));
        $this->assertSame('release.artist LIKE :token0', $query->getClause('artist')->getClause());
        
        // check for activated label clause
        $this->assertTrue($query->hasClause('label'));
        $this->assertTrue($query->isActive('label'));
        $this->assertInstanceOf('Sirprize\Tests\Queried\UseCaseQueryWithInlineAndExternalClauses\LabelClause', $query->getClause('label'));
        $this->assertSame('release.label = :token1', $query->getClause('label')->getClause());
    }
}