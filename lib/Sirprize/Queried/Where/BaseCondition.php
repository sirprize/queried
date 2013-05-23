<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried\Where;

/**
 * BaseCondition.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
 
class BaseCondition implements ConditionInterface
{

    protected $tokenizer = null;
    protected $values = array();
    protected $aliases = array();
    protected $clause = '';
    protected $params = array();
    protected $types = array();

    public function __construct(array $values = array(), array $aliases = array(), array $types = array(), Tokenizer $tokenizer = null)
    {
        $this
            ->setValues($values)
            ->setAliases($aliases)
            ->setTypes($types)
        ;

        if ($tokenizer)
        {
            $this->setTokenizer($tokenizer);
        }
    }
    
    public function setTokenizer(Tokenizer $tokenizer)
    {
        $this->tokenizer = $tokenizer;
        return $this;
    }
    
    public function getTokenizer()
    {
        if(!$this->tokenizer)
        {
            throw new ConditionException(sprintf('Call setTokenizer() before %s', __METHOD__));
        }
        
        return $this->tokenizer;
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

    public function getValues()
    {
        return $this->values;
    }
    
    public function getValue($name)
    {
        if(!array_key_exists($name, $this->values))
        {
            throw new ConditionException(sprintf('Missing valueument: "%s"', $name));
        }
        
        return $this->values[$name];
    }
    
    public function setAliases(array $aliases)
    {
        foreach($aliases as $name => $value)
        {
            $this->addAlias($name, $value);
        }

        return $this;
    }

    public function addAlias($name, $value)
    {
        $this->aliases[$name] = $value;
        return $this;
    }

    public function getAliases()
    {
        return $this->aliases;
    }
    
    public function getAlias($name)
    {
        if(!array_key_exists($name, $this->aliases))
        {
            throw new ConditionException(sprintf('Missing alias: "%s"', $name));
        }
        
        return $this->aliases[$name];
    }

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

    public function build()
    {
        return $this;
    }
}