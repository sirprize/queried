<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried;

/**
 * BaseClause.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
 
class BaseClause implements ClauseInterface
{

    protected $tokenizer = null;
    protected $args = array();
    protected $aliases = array();
    protected $clause = '';
    protected $params = array();
    protected $types = array();

    public function __construct(array $args = array(), array $aliases = array(), array $types = array(), Tokenizer $tokenizer = null)
    {
        $this
            ->setArgs($args)
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
            throw new QueryException(sprintf('Call setTokenizer() before %s', __METHOD__));
        }
        
        return $this->tokenizer;
    }
    
    public function setArgs(array $args)
    {
        foreach($args as $name => $value)
        {
            $this->addArg($name, $value);
        }

        return $this;
    }

    public function addArg($name, $value)
    {
        $this->args[$name] = $value;
        return $this;
    }

    public function getArgs()
    {
        return $this->args;
    }
    
    public function getArg($name)
    {
        if(!array_key_exists($name, $this->args))
        {
            throw new QueryException(sprintf('Missing argument: "%s"', $name));
        }
        
        return $this->args[$name];
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
            throw new QueryException(sprintf('Missing alias: "%s"', $name));
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