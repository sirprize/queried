<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\UseCaseQueryWithInlineAndExternalClauses;

class QueryTest extends \PHPUnit_Framework_TestCase
{
    public function testActivateSomeClausesByConfig()
    {
        $query = new ReleaseQuery();

        $query->activateClauses(
            array(
                'artist' => array()
            )
        );

        $this->assertTrue($query->hasClause('artist'));
        $this->assertTrue($query->isActive('artist'));
        $this->assertTrue($query->hasClause('label'));
        $this->assertFalse($query->isActive('label'));
    }
    
    public function testActivateAllClausesByConfig()
    {
        $query = new ReleaseQuery();

        $query->activateClauses(
            array(
                'artist' => array(),
                'label' => array()
            )
        );

        $this->assertTrue($query->hasClause('artist'));
        $this->assertTrue($query->isActive('artist'));
        $this->assertTrue($query->hasClause('label'));
        $this->assertTrue($query->isActive('label'));
    }
    
    public function testManualClauseActivation()
    {
        $query = new ReleaseQuery();

        $query
            ->activateClause('artist', array())
            ->activateClause('label', array())
        ;

        $this->assertTrue($query->hasClause('artist'));
        $this->assertTrue($query->isActive('artist'));
        $this->assertTrue($query->hasClause('label'));
        $this->assertTrue($query->isActive('label'));
    }
}