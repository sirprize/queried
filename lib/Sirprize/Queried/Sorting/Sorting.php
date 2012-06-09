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
    protected $rules = array();
    protected $params = array();
    
    public function __construct(Rules $rules, Params $params)
    {
        $this->rules = $rules;
        $this->params = $params;
    }
    
    public function getExpressions()
    {
        $rules = $this->rules;
        
        $getSortingExpressions = function($params) use ($rules)
        {
            $expressions = array();
            
            foreach($params as $rule => $direction)
            {
                foreach($rules->findExpressions($rule, $direction) as $sort => $order)
                {
                    $expressions[$sort] = $order;
                }
            }
            
            return $expressions;
        };
        
        $expressions = $getSortingExpressions($this->params->get());
        
        if(!count($expressions))
        {
            $expressions = $getSortingExpressions($this->params->getDefaults());
        }
        
        return $expressions;
    }
}