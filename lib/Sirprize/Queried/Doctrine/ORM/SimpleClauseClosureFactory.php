<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried\Doctrine\ORM;

use Sirprize\Queried\Tokenizer;
use Sirprize\Queried\BaseClause;
use Sirprize\Queried\QueryException;

/**
 * SimpleClauseClosureFactory.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
 
class SimpleClauseClosureFactory
{
    protected $tokenizer = null;
    
    public function __construct(Tokenizer $tokenizer)
    {
        $this->tokenizer = $tokenizer;
    }
    
    public function like($field, $alias = '')
    {
        return $this->getClauseClosure($field, $alias, 'like');
    }
    
    public function is($field, $alias = '')
    {
        return $this->getClauseClosure($field, $alias, 'is');
    }
    
    public function not($field, $alias = '')
    {
        return $this->getClauseClosure($field, $alias, 'not');
    }
    
    protected function getClauseClosure($field, $alias, $operation)
    {
        if(!preg_match('/(is|not|like)/', $operation))
        {
            throw new QueryException(sprintf('Invalid operation: "%s"', $operation));
        }
        
        $tokenizer = $this->tokenizer;

        return function($args) use ($tokenizer, $field, $alias, $operation)
        {
            try {
                $clause = new BaseClause($args);
                $token = $tokenizer->make();
                $alias .= ($alias) ? '.' : '';
                
                if($operation == 'is')
                {
                    $clause
                        ->setClause("{$alias}$field = :$token")
                        ->addParam($token, $clause->getArg('value'))
                    ;
                }
                else if($operation == 'not')
                {
                    $clause
                        ->setClause("{$alias}$field != :$token")
                        ->addParam($token, $clause->getArg('value'))
                    ;
                }
                else if($operation == 'like')
                {
                    $clause
                        ->setClause("{$alias}$field LIKE :$token")
                        ->addParam($token, '%'.$clause->getArg('value').'%')
                    ;
                }

                return $clause;
            }
            catch(\Exception $e) {
                throw new QueryException(sprintf('Error on field "%s": %s', $field, $e->getMessage()));
            }
        };
    }
}