<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\UseCaseQueryWithExternalClauses;

use Sirprize\Queried\BaseClause;

class ArtistClause extends BaseClause
{
    
    public function build()
    {
        // these must be set before calling build()
        $alias  = $this->getAlias('release');
        $artist = $this->getArg('artist');
        
        // run
        $token = $this->getTokenizer()->make();
        $alias .= ($alias) ? '.' : '';

        $this
            ->setClause("{$alias}artist LIKE :$token")
            ->addParam($token, '%'.$artist.'%')
        ;
        
        return $this;
    }
}