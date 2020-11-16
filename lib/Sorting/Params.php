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
    protected $rule = null;
    protected $direction = null;

    public function __construct($rule = null, $direction = null)
    {
        $this->rule = $rule;
        $this->direction = $direction;
    }

    public function setRule($rule)
    {
        $this->rule = $rule;
        return $this;
    }

    public function getRule()
    {
        return $this->rule;
    }

    public function setDirection($direction)
    {
        $this->direction = $direction;
        return $this;
    }

    public function getDirection()
    {
        return $this->direction;
    }
}