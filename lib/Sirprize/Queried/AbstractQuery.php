<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried;

use Sirprize\Paginate\Range\RangeInterface;
use Sirprize\Queried\Sorting\Params;
use Sirprize\Queried\Sorting\Rules;
use Sirprize\Queried\Sorting\Sorting;
use Sirprize\Queried\Where\Tokenizer;

/**
 * AbstractQuery.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
 
abstract class AbstractQuery
{
    protected $registeredConditions = array();
    protected $activeConditions = array();
    protected $tokenizer = null;
    protected $range = null;
    protected $sortingRules = array();
    protected $sortingParams = array();

    public function registerConditions(array $conditions)
    {
        foreach($conditions as $name => $condition)
        {
            $this->registerCondition($name, $condition);
        }

        return $this;
    }

    public function registerCondition($name, $condition)
    {
        $this->registeredConditions[$name] = $condition;
        return $this;
    }

    public function activateConditions(array $conditions)
    {
        foreach($conditions as $name => $values)
        {
            $this->activateCondition($name, $values);
        }

        return $this;
    }
    
    public function activateCondition($name, $values = array())
    {
        if(!$this->hasCondition($name))
        {
            throw new QueryException(sprintf('No condition registered for key: "%s"', $name));
        }
        
        if(is_callable($this->registeredConditions[$name]))
        {
            $this->activeConditions[$name] = $this->registeredConditions[$name]($values);
        }
        else {
            $this->activeConditions[$name] = new $this->registeredConditions[$name]($values);

        }
        
        return $this;
    }

    public function hasCondition($name)
    {
        return array_key_exists($name, $this->registeredConditions) || array_key_exists($name, $this->activeConditions);
    }
    
    public function hasActiveCondition($name)
    {
        return array_key_exists($name, $this->activeConditions);
    }

    public function setRange(RangeInterface $range)
    {
        $this->range = $range;
        return $this;
    }
    
    public function setSortingParams(Params $sortingParams)
    {
        $this->sortingParams = $sortingParams;
        return $this;
    }

    protected function getActiveConditions()
    {
        return $this->activeConditions;
    }

    protected function getActiveCondition($name)
    {
        if(!$this->hasActiveCondition($name))
        {
            throw new QueryException(sprintf('No active condition for key: "%s"', $name));
        }
        
        return $this->activeConditions[$name];
    }
    
    protected function getTokenizer()
    {
        if(!$this->tokenizer)
        {
            $this->tokenizer = new Tokenizer();
        }
        
        return $this->tokenizer;
    }
    
    protected function getSortingParams()
    {
        if(!$this->sortingParams)
        {
            $this->sortingParams = new Params();
        }
        
        return $this->sortingParams;
    }
    
    protected function getSortingRules()
    {
        if(!$this->sortingRules)
        {
            $this->sortingRules = new Rules();
        }
        
        return $this->sortingRules;
    }
    
    protected function getSorting()
    {
        return new Sorting($this->getSortingRules(), $this->getSortingParams());
    }

    protected function getRange()
    {
        if(!$this->range)
        {
            throw new QueryException(sprintf('Call setRange() before "%s"', __METHOD__));
        }
        
        return $this->range;
    }
}