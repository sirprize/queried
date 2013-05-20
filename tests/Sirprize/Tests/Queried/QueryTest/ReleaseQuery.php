<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\UseCaseQueryWithInlineAndExternalClauses;

use Sirprize\Queried\AbstractQuery;
use Sirprize\Queried\BaseClause;

class ReleaseQuery extends AbstractQuery
{
    protected $alias = 'release';
    
    public function __construct()
    {
        $artist = function($args)
        {
            return new BaseClause($args);
        };
        
        $this->registerClauses(
            array(
                'artist' => $artist, // inline clause
                'label' => 'Sirprize\Tests\Queried\UseCaseQueryWithInlineAndExternalClauses\LabelClause' // external clause
            )
        );
    }
}