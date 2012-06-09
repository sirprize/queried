<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\Doctrine\ORM\UseCaseQueryWithBaseClauses;

use Sirprize\Queried\Doctrine\ORM\AbstractDoctrineQuery;

class ReleaseQuery extends AbstractDoctrineQuery
{
    public function setup()
    {
        $alias = 'release';
        
        $this->available = array(
            'artist' => $this->buildFieldLikeValClause('artist', $alias),
            'label' => $this->buildFieldIsValClause('label', $alias)
        );
    }
    
    public function build()
    {
        foreach($this->active as $clause)
        {
            // do something
        }
    }
}