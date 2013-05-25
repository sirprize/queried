<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\UseCaseQueryWithInlineAndExternalConditions;

use Sirprize\Queried\BaseQuery;
use Sirprize\Queried\Condition\BaseCondition;

class ReleaseQuery extends BaseQuery
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
                'label' => new LabelCondition() // external condition
            )
        );
    }
}