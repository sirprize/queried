<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Queried;

use Sirprize\Queried\Exception\InvalidArgumentException;
use Sirprize\Queried\Condition\ConditionInterface;
use Sirprize\Queried\Condition\Tokenizer;
use Sirprize\Queried\Sorting\Sorting;

/**
 * BaseQueryBuilder.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */

class BaseQueryBuilder
{
    protected $registeredConditions = array();
    protected $activeConditions = array();
    protected $tokenizer = null;
    protected $sorting = null;

    public function registerConditions(array $conditions)
    {
        foreach ($conditions as $name => $condition)
        {
            $this->registerCondition($name, $condition);
        }

        return $this;
    }

    public function registerCondition($name, ConditionInterface $condition)
    {
        $this->registeredConditions[$name] = $condition;
        return $this;
    }

    public function activateConditions(array $conditions)
    {
        foreach ($conditions as $name => $values)
        {
            $this->activateCondition($name, $values);
        }

        return $this;
    }

    public function activateCondition($name, array $values = array())
    {
        if (!$this->hasCondition($name))
        {
            throw new InvalidArgumentException(sprintf('No condition registered for key: "%s"', $name));
        }

        $this->activeConditions[$name] = $this->registeredConditions[$name]->setValues($values);

        return $this;
    }

    public function hasCondition($name)
    {
        return array_key_exists($name, $this->registeredConditions) || array_key_exists($name, $this->activeConditions);
    }

    public function hasActiveCondition($name)
    {
        return array_key_exists($name, $this->activeConditions);
    }

    public function getSorting()
    {
        if (!$this->sorting)
        {
            $this->sorting = new Sorting();
        }

        return $this->sorting;
    }

    public function getActiveConditions()
    {
        return $this->activeConditions;
    }

    protected function getActiveCondition($name)
    {
        if (!$this->hasActiveCondition($name))
        {
            throw new InvalidArgumentException(sprintf('No active condition for key: "%s"', $name));
        }

        return $this->activeConditions[$name];
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