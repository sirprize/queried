<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\UseCaseQueryWithInlineAndExternalConditions;

use Sirprize\Queried\AbstractQuery;
use Sirprize\Queried\Where\BaseCondition;

class ReleaseQuery extends AbstractQuery
{
    protected $alias = 'release';
    
    public function __construct()
    {
        $artist = function($values)
        {
            return new BaseCondition($values);
        };
        
        $this->registerConditions(
            array(
                'artist' => $artist, // inline condition
                'label' => 'Sirprize\Tests\Queried\UseCaseQueryWithInlineAndExternalConditions\LabelCondition' // external condition
            )
        );
    }
}