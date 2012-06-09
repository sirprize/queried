<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried\Sorting;

/**
 * Rules.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
 
class Rules
{
    protected $rules = array();
    
    public function addRule($name, Rule $rule)
    {
        $this->rules[$name] = $rule;
        return $this;
    }
    
    public function getRules()
    {
        return $this->rules;
    }
    
    public function findExpressions($ruleName, $ruleOrder)
    {
        if(array_key_exists($ruleName, $this->rules))
        {
            $ruleOrder = strtolower($ruleOrder);
            $ruleOrder = ($ruleOrder == 'asc' || $ruleOrder == 'desc') ? $ruleOrder : null;
            $ruleOrder = ($ruleOrder) ? $ruleOrder : $this->rules[$ruleName]->getDefaultOrder();
            $ruleOrder = ($ruleOrder) ? $ruleOrder : 'asc';
            
            if($ruleOrder == 'asc')
            {
                return $this->rules[$ruleName]->getAscExpressions();
            }
            else {
                return $this->rules[$ruleName]->getDescExpressions();
            }
        }
        
        return array();
    }
    
    public function newRule()
    {
        return new Rule();
    }
}