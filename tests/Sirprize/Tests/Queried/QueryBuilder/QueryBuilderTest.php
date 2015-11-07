<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Tests\Queried\QueryBuilderTest;

require_once 'ReleaseQueryBuilder.php';

class QueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testActivateSomeConditionsByConfig()
    {
        $queryBuilder = new ReleaseQueryBuilder();

        $queryBuilder->activateConditions(
            array(
                'artist' => array()
            )
        );

        $this->assertTrue($queryBuilder->hasCondition('artist'));
        $this->assertTrue($queryBuilder->hasActiveCondition('artist'));
        $this->assertTrue($queryBuilder->hasCondition('label'));
        $this->assertFalse($queryBuilder->hasActiveCondition('label'));
    }
    
    public function testActivateAllConditionsByConfig()
    {
        $queryBuilder = new ReleaseQueryBuilder();
 
        $queryBuilder->activateConditions(
            array(
                'artist' => array(),
                'label' => array()
            )
        );

        $this->assertTrue($queryBuilder->hasCondition('artist'));
        $this->assertTrue($queryBuilder->hasActiveCondition('artist'));
        $this->assertTrue($queryBuilder->hasCondition('label'));
        $this->assertTrue($queryBuilder->hasActiveCondition('label'));
    }
    
    public function testManualConditionActivation()
    {
        $queryBuilder = new ReleaseQueryBuilder();

        $queryBuilder
            ->activateCondition('artist', array())
            ->activateCondition('label', array())
        ;

        $this->assertTrue($queryBuilder->hasCondition('artist'));
        $this->assertTrue($queryBuilder->hasActiveCondition('artist'));
        $this->assertTrue($queryBuilder->hasCondition('label'));
        $this->assertTrue($queryBuilder->hasActiveCondition('label'));
    }
}