<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Queried;

use Sirprize\Queried\Condition\ConditionInterface;
use Sirprize\Queried\Condition\Registry;
use Sirprize\Queried\Condition\Tokenizer;
use Sirprize\Queried\Sorting\Sorting;

/**
 * BaseQueryConfigurator.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */

class BaseQueryConfigurator
{
    protected $conditionRegistry = null;
    protected $tokenizer = null;
    protected $sorting = null;

    public function setConditionRegistry(Registry $conditionRegistry)
    {
        $this->conditionRegistry = $conditionRegistry;
    }

    public function getConditionRegistry()
    {
        if (!$this->conditionRegistry)
        {
            $this->conditionRegistry = new Registry();
        }

        return $this->conditionRegistry;
    }

    public function getSorting()
    {
        if (!$this->sorting)
        {
            $this->sorting = new Sorting();
        }

        return $this->sorting;
    }

    protected function getTokenizer()
    {
        if (!$this->tokenizer)
        {
            $this->tokenizer = new Tokenizer();
        }

        return $this->tokenizer;
    }
}