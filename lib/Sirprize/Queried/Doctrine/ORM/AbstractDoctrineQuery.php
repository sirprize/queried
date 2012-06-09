<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Sirprize\Queried\AbstractQuery;
use Sirprize\Queried\BaseClause;

/**
 * AbstractDoctrineQuery.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
 
abstract class AbstractDoctrineQuery extends AbstractQuery
{
    protected $queryBuilder = null;
    
    public function setQueryBuilder(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
        return $this;
    }
    
    public function getQueryBuilder()
    {
        if(!$this->queryBuilder)
        {
            throw new \Exception(sprintf('Call setQueryBuilder() before "%s"', __METHOD__));
        }
        
        return $this->queryBuilder;
    }
    
    public function applyRange($totalItems)
    {
        $this->getRange()->setTotalItems($totalItems);
        
        $this->getQueryBuilder()
            ->setFirstResult($this->getRange()->getOffset())
            ->setMaxResults($this->getRange()->getNumItems())
        ;
    }
    
    public function applySorting()
    {
        foreach($this->getSortingExpressions() as $sort => $order)
        {
            $this->getQueryBuilder()->addOrderBy($sort, $order);
        }
    }
    
    public function buildFieldLikeValClause($field, $alias)
    {
        return $this->buildFieldClause($field, $alias, 'like');
    }
    
    public function buildFieldIsValClause($field, $alias)
    {
        return $this->buildFieldClause($field, $alias, 'is');
    }
    
    public function buildFieldIsNotValClause($field, $alias)
    {
        return $this->buildFieldClause($field, $alias, 'not');
    }
    
    protected function buildFieldClause($field, $alias, $operation)
    {
        if(!preg_match('/(is|not|like)/', $operation))
        {
            throw new QueryException(sprintf('Invalid operation: "%s"', $operation));
        }
        
        $tokenizer = $this->getTokenizer();

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