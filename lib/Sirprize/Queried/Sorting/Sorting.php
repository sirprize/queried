<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried\Sorting;

/**
 * Sorting.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
 
class Sorting
{
    protected $rules = null;
    protected $params = null;
    protected $defaults = null;

    public function setRules(Rules $rules)
    {
        $this->rules = $rules;
        return $this;
    }

    public function getRules()
    {
        if (!$this->rules)
        {
            $this->rules = new Rules();
        }

        return $this->rules;
    }

    public function setParams(Params $params)
    {
        $this->params = $params;
        return $this;
    }

    public function getParams()
    {
        if (!$this->params)
        {
            $this->params = new Params();
        }

        return $this->params;
    }

    public function setDefaults(Params $defaults)
    {
        $this->defaults = $defaults;
        return $this;
    }

    public function getDefaults()
    {
        if (!$this->defaults)
        {
            $this->defaults = new Params();
        }

        return $this->defaults;
    }

    public function getColumns()
    {
        if (!$this->rules)
        {
            return array();
        }

        $expressions = array();
        $rules = $this->rules;

        $getColumns = function($params) use ($rules)
        {
            $expressions = array();
            
            foreach($params as $rule => $direction)
            {
                foreach($rules->findColumns($rule, $direction) as $sort => $order)
                {
                    $expressions[$sort] = $order;
                }
            }
            
            return $expressions;
        };
        
        if ($this->params)
        {
            $expressions = $getColumns($this->params->get());
        }

        if(!count($expressions) && $this->defaults)
        {
            $expressions = $getColumns($this->defaults->get());
        }
        
        return $expressions;
    }
}