<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried\Sorting\Factory;

use Sirprize\Queried\Sorting\Input;

/**
 * Input.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
class InputFactory
{
    public function getInstance(array $input = array(), array $defaults = array())
    {
        return new Input($input, $defaults);
    }
}