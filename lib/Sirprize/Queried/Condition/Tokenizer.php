<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Queried\Condition;

/**
 * Tokenizer.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
 
class Tokenizer
{
    protected $prefix = null;
    protected $count = 0;

    public function __construct($prefix = 'token')
    {
        $this->prefix = (string) (!$prefix) ? 'token' : $prefix;
    }
    
    public function make()
    {
        return $this->prefix.$this->count++;
    }
}