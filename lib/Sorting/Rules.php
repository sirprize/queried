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
    protected $rules = [];

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
        $this->rules = [];

        foreach ($rules as $name => $rule)
        {
            $this->addRule($name, $rule);
        }
    }

    public function hasRule($rule)
    {
        return array_key_exists($rule, $this->rules);
    }

    public function getRule($name)
    {
        return
            ($this->hasRule($name))
            ? $this->rules[$name]
            : null
        ;
    }
}