<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried\Sorting;

/**
 * Input.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
class Input
{
    protected $input = array();
    protected $defaults = array();
    
    public function __construct(array $input = array(), array $defaults = array())
    {
        $this->set($input);
        $this->setDefaults($defaults);
    }
    
    public function set(array $input)
    {
        $this->input = array();
        
        foreach($input as $sort => $order)
        {
            $this->add($sort, $order);
        }
        
        return $this;
    }
    
    public function add($sort, $order)
    {
        $this->input[$sort] = $order;
        return $this;
    }
    
    public function get()
    {
        return $this->input;
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