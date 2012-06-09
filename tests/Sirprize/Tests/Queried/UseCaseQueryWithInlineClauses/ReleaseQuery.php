<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\UseCaseQueryWithInlineClauses;

use Sirprize\Queried\AbstractQuery;
use Sirprize\Queried\BaseClause;

class ReleaseQuery extends AbstractQuery
{
    public function setup()
    {
        $tokenizer = $this->getTokenizer();
        $alias = 'release';
        
        // artist clause
        $artist = function($args) use ($tokenizer, $alias)
        {
            $clause = new BaseClause($args);
            $token = $tokenizer->make();
            $alias .= ($alias) ? '.' : '';
            
            $clause
                ->setClause("{$alias}artist LIKE :$token")
                ->addParam($token, '%'.$clause->getArg('artist').'%')
            ;
            
            return $clause;
        };
        
        // label clause
        $label = function($args) use ($tokenizer, $alias)
        {
            $clause = new BaseClause($args);
            $token = $tokenizer->make();
            $alias .= ($alias) ? '.' : '';
            
            $clause
                ->setClause("{$alias}label = :$token")
                ->addParam($token, $clause->getArg('label'))
            ;
            
            return $clause;
        };
        
        $this->available = array(
            'artist' => $artist,
            'label' => $label
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