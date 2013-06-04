<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried\Condition;

/**
 * BaseCondition.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
 
class BaseCondition implements ConditionInterface
{
    protected $clause = '';
    protected $params = array();
    protected $types = array();
    protected $values = array();

    public function setClause($clause)
    {
        $this->clause = $clause;
        return $this;
    }

    public function getClause()
    {
        return $this->clause;
    }

    public function setParams(array $params)
    {
        foreach($params as $name => $value)
        {
            $this->addParam($name, $value);
        }

        return $this;
    }

    public function addParam($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setTypes(array $types)
    {
        foreach($types as $name => $type)
        {
            $this->addType($name, $type);
        }

        return $this;
    }

    public function addType($name, $type)
    {
        $this->types[$name] = $type;
        return $this;
    }

    public function getTypes()
    {
        return $this->types;
    }

    public function getType($name)
    {
        if(!array_key_exists($name, $this->types))
        {
            return null;
        }
        
        return $this->types[$name];
    }

    public function build(Tokenizer $tokenizer = null)
    {
        return $this;
    }

    public function setValues(array $values)
    {
        foreach($values as $name => $value)
        {
            $this->addValue($name, $value);
        }

        return $this;
    }

    public function addValue($name, $value)
    {
        $this->values[$name] = $value;
        return $this;
    }

    protected function getValues()
    {
        return $this->values;
    }
    
    protected function getValue($name)
    {
        if(!array_key_exists($name, $this->values))
        {
            return null;
        }
        
        return $this->values[$name];
    }
}