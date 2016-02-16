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
        $rule = null;
        $direction = null;

        if ($this->params)
        {
            $rule = $this->rules->getRule($this->params->getRule());
            $direction = $this->params->getDirection();
        }

        if (!$rule && $this->defaults)
        {
            $rule = $this->rules->getRule($this->defaults->getRule());
            $direction = $this->defaults->getDirection();
        }

        return
            ($rule)
            ? $rule->getColumns($direction)
            : []
        ;
    }

    public function getApplicableRule()
    {
        $rule = null;
        $name = null;

        if ($this->params)
        {
            $name = $this->params->getRule();
            $rule = $this->rules->getRule($name);
        }

        if (!$rule && $this->defaults)
        {
            $name = $this->defaults->getRule();
            $rule = $this->rules->getRule($name);
        }

        return ($rule) ? $name : null;
    }

    public function getApplicableDirection()
    {
        $rule = null;
        $direction = null;

        if ($this->params)
        {
            $rule = $this->rules->getRule($this->params->getRule());
            $direction = $this->params->getDirection();
        }

        if (!$rule && $this->defaults)
        {
            $rule = $this->rules->getRule($this->defaults->getRule());
            $direction = $this->defaults->getDirection();
        }

        return
            ($rule)
            ? $rule->getApplicableDirection($direction)
            : null
        ;
    }
}