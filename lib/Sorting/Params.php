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

    public function __construct(array $params = array())
    {
        $this->params = $params;
    }

    public function set(array $params)
    {
        $this->params = $params;
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
}