<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\Doctrine\ORM\UseCaseQueryWithBaseClauses;

class UseCaseQueryWithBaseClausesTest extends \PHPUnit_Framework_TestCase
{
    public function testActivateSomeClausesByConfig()
    {
        $config = array(
            'activate' => array(
                'artist' => array('value' => 'rebolledo')
            )
        );
        
        $query = new ReleaseQuery($config);
        
        // check for activated artist clause
        $this->assertTrue($query->hasClause('artist'));
        $this->assertTrue($query->isActive('artist'));
        $this->assertInstanceOf('Sirprize\Queried\BaseClause', $query->getClause('artist'));
        $this->assertSame('release.artist LIKE :token0', $query->getClause('artist')->getClause());
        
        // check for inactive label clause
        $this->assertTrue($query->hasClause('label'));
        $this->assertFalse($query->isActive('label'));
        #die($query->getClause('artist')->getClause());
    }
    
    public function testActivateAllClausesByConfig()
    {
        $config = array(
            'activate' => array(
                'artist' => array('value' => 'rebolledo'),
                'label' => array('value' => 'comeme')
            )
        );
        
        $query = new ReleaseQuery($config);
        
        // check for activated artist clause
        $this->assertTrue($query->hasClause('artist'));
        $this->assertTrue($query->isActive('artist'));
        $this->assertInstanceOf('Sirprize\Queried\BaseClause', $query->getClause('artist'));
        $this->assertSame('release.artist LIKE :token0', $query->getClause('artist')->getClause());
        
        // check for activated label clause
        $this->assertTrue($query->hasClause('label'));
        $this->assertTrue($query->isActive('label'));
        $this->assertInstanceOf('Sirprize\Queried\BaseClause', $query->getClause('label'));
        $this->assertSame('release.label = :token1', $query->getClause('label')->getClause());
    }
    
    public function testManualClauseActivation()
    {
        $query = new ReleaseQuery();
        $query->activateClause('artist', array('value' => 'rebolledo'));
        $query->activateClause('label', array('value' => 'comeme'));
        
        // check for activated artist clause
        $this->assertTrue($query->hasClause('artist'));
        $this->assertTrue($query->isActive('artist'));
        $this->assertInstanceOf('Sirprize\Queried\BaseClause', $query->getClause('artist'));
        $this->assertSame('release.artist LIKE :token0', $query->getClause('artist')->getClause());
        
        // check for activated label clause
        $this->assertTrue($query->hasClause('label'));
        $this->assertTrue($query->isActive('label'));
        $this->assertInstanceOf('Sirprize\Queried\BaseClause', $query->getClause('label'));
        $this->assertSame('release.label = :token1', $query->getClause('label')->getClause());
    }
}