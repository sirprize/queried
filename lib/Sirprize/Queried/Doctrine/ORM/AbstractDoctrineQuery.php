<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Sirprize\Queried\BaseQuery;

/**
 * AbstractDoctrineQuery.
 *
 * @deprecated
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
 
abstract class AbstractDoctrineQuery extends BaseQuery
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
        if (!$this->getRange())
        {
            return;
        }

        $this->getRange()->setTotalItems($totalItems);
        
        $this->getQueryBuilder()
            ->setFirstResult($this->getRange()->getOffset())
            ->setMaxResults($this->getRange()->getNumItems())
        ;
    }
    
    public function applySorting()
    {
        foreach($this->getSorting()->getColumns() as $column => $order)
        {
            $this->getQueryBuilder()->addOrderBy($column, $order);
        }
    }
    
    public function registerSimpleLikeCondition($name, $field, $alias = '')
    {
        $factory = new SimpleConditionClosureFactory($this->getTokenizer());
        return $this->registerCondition($name, $factory->like($field, $alias));
    }

    public function registerSimpleIsCondition($name, $field, $alias = '')
    {
        $factory = new SimpleConditionClosureFactory($this->getTokenizer());
        return $this->registerCondition($name, $factory->is($field, $alias));
    }

    public function registerSimpleNotCondition($name, $field, $alias = '')
    {
        $factory = new SimpleConditionClosureFactory($this->getTokenizer());
        return $this->registerCondition($name, $factory->not($field, $alias));
    }
}