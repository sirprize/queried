<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Sirprize\Queried\AbstractQuery;

/**
 * AbstractDoctrineQuery.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
 
abstract class AbstractDoctrineQuery extends AbstractQuery
{
    protected $queryBuilder = null;
    
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }
    
    public function getQueryBuilder()
    {
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
    
    public function registerSimpleLikeClause($name, $field, $alias = '')
    {
        $factory = new SimpleClauseClosureFactory($this->getTokenizer());
        $this->registerClause($name, $factory->like($field, $alias));
        return $this;
    }

    public function registerSimpleIsClause($name, $field, $alias = '')
    {
        $factory = new SimpleClauseClosureFactory($this->getTokenizer());
        $this->registerClause($name, $factory->is($field, $alias));
        return $this;
    }

    public function registerSimpleNotClause($name, $field, $alias = '')
    {
        $factory = new SimpleClauseClosureFactory($this->getTokenizer());
        $this->registerClause($name, $factory->not($field, $alias));
        return $this;
    }
}