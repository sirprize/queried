<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried;

use Sirprize\Paginate\Range\RangeInterface;
use Sirprize\Queried\Sorting\Params;
use Sirprize\Queried\Sorting\Rules;
use Sirprize\Queried\Sorting\Sorting;

/**
 * AbstractQuery.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
 
abstract class AbstractQuery
{
    protected $active = array();
    protected $available = array();
    protected $tokenizer = null;
    protected $range = null;
    protected $sortingRules = array();
    protected $sortingParams = array();

    public function __construct(array $config = array())
    {
        $this->setup();
        
        if(array_key_exists('activate', $config))
        {
            foreach($config['activate'] as $name => $args)
            {
                $this->activateClause($name, $args);
            }
        }
    }
    
    public function activateClause($name, $args = array())
    {
        if(!$this->hasClause($name))
        {
            throw new QueryException(sprintf('No clause class available for key: "%s"', $name));
        }
        
        if(is_callable($this->available[$name]))
        {
            $this->addClause($name, $this->available[$name]($args));
        }
        else {
            $this->addClause($name, new $this->available[$name]($args));
        }
        
        return $this;
    }
    
    public function addClause($name, ClauseInterface $clause)
    {
        $this->active[$name] = $clause;
        return $this;
    }
    
    public function hasClause($name)
    {
        return array_key_exists($name, $this->available) || array_key_exists($name, $this->active);
    }
    
    public function isActive($name)
    {
        return array_key_exists($name, $this->active);
    }
    
    public function getClause($name)
    {
        if(!$this->isActive($name))
        {
            throw new QueryException(sprintf('No active clause available for key: "%s"', $name));
        }
        
        return $this->active[$name];
    }
    
    public function getTokenizer()
    {
        if(!$this->tokenizer)
        {
            $this->tokenizer = new Tokenizer();
        }
        
        return $this->tokenizer;
    }
    
    public function setRange(RangeInterface $range)
    {
        $this->range = $range;
        return $this;
    }
    
    public function getRange()
    {
        if(!$this->range)
        {
            throw new QueryException(sprintf('Call setRange() before "%s"', __METHOD__));
        }
        
        return $this->range;
    }
    
    public function setSortingParams(Params $sortingParams)
    {
        $this->sortingParams = $sortingParams;
        return $this;
    }
    
    public function getSortingParams()
    {
        if(!$this->sortingParams)
        {
            throw new QueryException(sprintf('Call setSortingParams() before "%s"', __METHOD__));
        }
        
        return $this->sortingParams;
    }
    
    public function getSortingRules()
    {
        if(!$this->sortingRules)
        {
            $this->sortingRules = new Rules();
        }
        
        return $this->sortingRules;
    }
    
    public function getSortingExpressions()
    {
        $sorting = new Sorting($this->getSortingRules(), $this->getSortingParams());
        return $sorting->getExpressions();
    }
    
    protected function setup()
    {}
}