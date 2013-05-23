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
    
    public function __construct(Rules $rules, Params $params)
    {
        $this->rules = $rules;
        $this->params = $params;
    }
    
    public function getColumns()
    {
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
        
        $expressions = $getColumns($this->params->get());
        
        if(!count($expressions))
        {
            $expressions = $getColumns($this->params->getDefaults());
        }
        
        return $expressions;
    }
}