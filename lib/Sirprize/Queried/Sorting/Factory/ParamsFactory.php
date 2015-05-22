<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried\Sorting\Factory;

use Sirprize\Queried\Sorting\Params;

/**
 * Params.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
class ParamsFactory
{
    public function getInstance(array $input = array())
    {
        return new Params($input);
    }
}