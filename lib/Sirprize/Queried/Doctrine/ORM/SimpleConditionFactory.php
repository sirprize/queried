<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried\Doctrine\ORM;

use Sirprize\Queried\Condition\Tokenizer;
use Sirprize\Queried\Condition\BaseCondition;

/**
 * SimpleConditionFactory.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
 
class SimpleConditionFactory
{
    protected $tokenizer = null;
    
    public function __construct(Tokenizer $tokenizer)
    {
        $this->tokenizer = $tokenizer;
    }
    
    public function like($field, $alias = '')
    {
        return $this->getCondition($field, $alias, 'like');
    }
    
    public function is($field, $alias = '')
    {
        return $this->getCondition($field, $alias, 'is');
    }
    
    public function not($field, $alias = '')
    {
        return $this->getCondition($field, $alias, 'not');
    }
    
    protected function getCondition($field, $alias, $operation)
    {
        if(!preg_match('/(is|not|like)/', $operation))
        {
            throw new FactoryException(sprintf('Invalid operation: "%s"', $operation));
        }
        
        $tokenizer = $this->tokenizer;

        return function($values) use ($tokenizer, $field, $alias, $operation)
        {
            try {
                $condition = new BaseCondition();
                $token = $tokenizer->make();
                $alias .= ($alias) ? '.' : '';
                $value = (array_key_exists('value', $values)) ? $values['value'] : null;
                
                if($operation == 'is')
                {
                    $condition
                        ->setClause("{$alias}$field = :$token")
                        ->addParam($token, $value)
                    ;
                }
                else if($operation == 'not')
                {
                    $condition
                        ->setClause("{$alias}$field != :$token")
                        ->addParam($token, $value)
                    ;
                }
                else if($operation == 'like')
                {
                    $condition
                        ->setClause("{$alias}$field LIKE :$token")
                        ->addParam($token, '%'.$value.'%')
                    ;
                }

                return $condition;
            }
            catch(\Exception $e) {
                throw new FactoryException(sprintf('Error on field "%s": %s', $field, $e->getMessage()));
            }
        };
    }
}