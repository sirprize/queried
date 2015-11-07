<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried\Sorting;

/**
 * Rule.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
 
class Rule
{
    protected $ascColumns = array();
    protected $descColumns = array();
    protected $defaultOrder = null;
    
    public function addAscColumn($column, $order)
    {
        $this->ascColumns[$column] = $order;
        return $this;
    }
    
    public function getAscColumns()
    {
        return $this->ascColumns;
    }
    
    public function addDescColumn($column, $order)
    {
        $this->descColumns[$column] = $order;
        return $this;
    }
    
    public function getDescColumns()
    {
        return $this->descColumns;
    }
    
    public function setDefaultOrder($defaultOrder)
    {
        $this->defaultOrder = $defaultOrder;
        return $this;
    }
    
    public function getDefaultOrder()
    {
        return $this->defaultOrder;
    }
}