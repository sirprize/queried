<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Queried\Condition;

use Sirprize\Queried\Exception\InvalidArgumentException;

/**
 * Registry.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */

class Registry
{
	protected $registeredConditions = [];
    protected $activeConditions = [];

    public function __clone()
    {
        $this->activeConditions = [];

        foreach ($this->registeredConditions as $name => $condition)
        {
            $this->registerCondition($name, clone $condition);
        }
    }

    public function getRegisteredConditions()
    {
        return $this->registeredConditions;
    }

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

    public function activateCondition($name, array $values = [])
    {
        if (!$this->hasCondition($name))
        {
            $msg = sprintf('No condition registered for key: "%s"', $name);
            throw new InvalidArgumentException($msg);
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

    public function getActiveConditions()
    {
        return $this->activeConditions;
    }

    protected function getActiveCondition($name)
    {
        if (!$this->hasActiveCondition($name))
        {
            $msg = sprintf('No active condition for key: "%s"', $name);
            throw new InvalidArgumentException($msg);
        }

        return $this->activeConditions[$name];
    }
}