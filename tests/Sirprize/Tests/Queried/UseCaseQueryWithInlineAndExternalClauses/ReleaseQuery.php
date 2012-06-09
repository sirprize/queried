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
    
    public function setup()
    {
        $tokenizer = $this->getTokenizer();
        $alias = $this->alias;
        
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
        
        $this->available = array(
            'artist' => $artist,
            'label' => 'Sirprize\Tests\Queried\UseCaseQueryWithInlineAndExternalClauses\LabelClause'
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