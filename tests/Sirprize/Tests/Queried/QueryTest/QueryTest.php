<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\UseCaseQueryWithInlineAndExternalConditions;

class QueryTest extends \PHPUnit_Framework_TestCase
{
    public function testActivateSomeConditionsByConfig()
    {
        $query = new ReleaseQuery();

        $query->activateConditions(
            array(
                'artist' => array()
            )
        );

        $this->assertTrue($query->hasCondition('artist'));
        $this->assertTrue($query->hasActiveCondition('artist'));
        $this->assertTrue($query->hasCondition('label'));
        $this->assertFalse($query->hasActiveCondition('label'));
    }
    
    public function testActivateAllConditionsByConfig()
    {
        $query = new ReleaseQuery();

        $query->activateConditions(
            array(
                'artist' => array(),
                'label' => array()
            )
        );

        $this->assertTrue($query->hasCondition('artist'));
        $this->assertTrue($query->hasActiveCondition('artist'));
        $this->assertTrue($query->hasCondition('label'));
        $this->assertTrue($query->hasActiveCondition('label'));
    }
    
    public function testManualConditionActivation()
    {
        $query = new ReleaseQuery();

        $query
            ->activateCondition('artist', array())
            ->activateCondition('label', array())
        ;

        $this->assertTrue($query->hasCondition('artist'));
        $this->assertTrue($query->hasActiveCondition('artist'));
        $this->assertTrue($query->hasCondition('label'));
        $this->assertTrue($query->hasActiveCondition('label'));
    }
}