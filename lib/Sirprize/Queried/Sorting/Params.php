<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried\Sorting;

/**
 * Params.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
class Params
{
    protected $params = array();
    protected $defaults = array();
    
    public function __construct(array $params = array(), array $defaults = array())
    {
        $this->set($params);
        $this->setDefaults($defaults);
    }
    
    public function set(array $params)
    {
        $this->params = array();
        
        foreach($params as $sort => $order)
        {
            $this->add($sort, $order);
        }
        
        return $this;
    }
    
    public function add($sort, $order)
    {
        $this->params[$sort] = $order;
        return $this;
    }
    
    public function get()
    {
        return $this->params;
    }
    
    public function setDefaults(array $defaults)
    {
        $this->defaults = array();
        
        foreach($defaults as $sort => $order)
        {
            $this->addDefault($sort, $order);
        }
        
        return $this;
    }
    
    public function addDefault($sort, $order)
    {
        $this->defaults[$sort] = $order;
        return $this;
    }
    
    public function getDefaults()
    {
        return $this->defaults;
    }
}