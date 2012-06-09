<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\UseCaseQueryWithExternalClauses;

use Sirprize\Queried\AbstractQuery;

class ReleaseQuery extends AbstractQuery
{
    public function setup()
    {
        $this->available = array(
            'artist' => 'Sirprize\Tests\Queried\UseCaseQueryWithExternalClauses\ArtistClause',
            'label' => 'Sirprize\Tests\Queried\UseCaseQueryWithExternalClauses\LabelClause'
        );
    }
    
    public function build()
    {
        foreach($this->active as $clause)
        {
            // finalize "external" clauses (inline clauses are already set up due to the way they are constructed)
            $clause
                ->addAlias('release', 'release')
                ->setTokenizer($this->getTokenizer())
                ->build()
            ;
        }
    }
}