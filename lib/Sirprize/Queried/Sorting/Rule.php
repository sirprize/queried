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
    protected $ascExpressions = array();
    protected $descExpressions = array();
    protected $defaultOrder = null;
    
    public function addAscExpression($sort, $order)
    {
        $this->ascExpressions[$sort] = $order;
        return $this;
    }
    
    public function getAscExpressions()
    {
        return $this->ascExpressions;
    }
    
    public function addDescExpression($sort, $order)
    {
        $this->descExpressions[$sort] = $order;
        return $this;
    }
    
    public function getDescExpressions()
    {
        return $this->descExpressions;
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