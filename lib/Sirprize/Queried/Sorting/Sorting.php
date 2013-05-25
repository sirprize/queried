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
    protected $input = null;
    
    public function __construct(Rules $rules, Input $input)
    {
        $this->rules = $rules;
        $this->input = $input;
    }
    
    public function getColumns()
    {
        $rules = $this->rules;
        
        $getColumns = function($input) use ($rules)
        {
            $expressions = array();
            
            foreach($input as $rule => $direction)
            {
                foreach($rules->findColumns($rule, $direction) as $sort => $order)
                {
                    $expressions[$sort] = $order;
                }
            }
            
            return $expressions;
        };
        
        $expressions = $getColumns($this->input->get());
        
        if(!count($expressions))
        {
            $expressions = $getColumns($this->input->getDefaults());
        }
        
        return $expressions;
    }
}