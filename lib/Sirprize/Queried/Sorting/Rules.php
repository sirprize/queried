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

    public function newRule($name)
    {
        return $this->addRule($name, new Rule());
    }

    public function addRule($name, Rule $rule)
    {
        return $this->rules[$name] = $rule;
    }

    public function setRules(array $rules)
    {
        $this->rules = array();

        foreach($rules as $name => $rule)
        {
            $this->addRule($name, $rule);
        }
    }

    public function findColumns($ruleName, $ruleOrder)
    {
        if(array_key_exists($ruleName, $this->rules))
        {
            $ruleOrder = strtolower($ruleOrder);
            $ruleOrder = ($ruleOrder == 'asc' || $ruleOrder == 'desc') ? $ruleOrder : null;
            $ruleOrder = ($ruleOrder) ? $ruleOrder : $this->rules[$ruleName]->getDefaultOrder();
            $ruleOrder = ($ruleOrder) ? $ruleOrder : 'asc';
            
            if($ruleOrder == 'asc')
            {
                return $this->rules[$ruleName]->getAscColumns();
            }
            else {
                return $this->rules[$ruleName]->getDescColumns();
            }
        }
        
        return array();
    }
}