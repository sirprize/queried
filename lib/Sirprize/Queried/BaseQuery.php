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
use Sirprize\Queried\Condition\Tokenizer;
use Sirprize\Queried\Condition\ConditionInterface;

/**
 * BaseQuery.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
 
class BaseQuery
{
    protected $registeredConditions = array();
    protected $activeConditions = array();
    protected $tokenizer = null;
    protected $range = null;
    protected $sorting = null;

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

    public function activateCondition($name, array $values = array())
    {
        if(!$this->hasCondition($name))
        {
            throw new QueryException(sprintf('No condition registered for key: "%s"', $name));
        }
        
        if($this->registeredConditions[$name] instanceof ConditionInterface)
        {
            $this->activeConditions[$name] = $this->registeredConditions[$name]->setValues($values);
        }
        else if(is_callable($this->registeredConditions[$name]))
        {
            $this->activeConditions[$name] = $this->registeredConditions[$name]($values);
        }
        else {
            $this->activeConditions[$name] = new $this->registeredConditions[$name];
            $this->activeConditions[$name]->setValues($values);
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

    public function getRange()
    {
        return $this->range;
    }

    public function getSorting()
    {
        if(!$this->sorting)
        {
            $this->sorting = new Sorting();
            $this->sorting->setParams(new Params());
            $this->sorting->setDefaults(new Params());
            $this->sorting->setRules(new Rules());
        }

        return $this->sorting;
    }

    public function getActiveConditions()
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
}