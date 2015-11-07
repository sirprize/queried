<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\QueryBuilderTest;

require_once 'LabelCondition.php';

use Sirprize\Queried\BaseQueryBuilder;
use Sirprize\Queried\Condition\BaseCondition;

class ReleaseQueryBuilder extends BaseQueryBuilder
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