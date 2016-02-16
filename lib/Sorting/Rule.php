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
    protected $ascColumns = [];
    protected $descColumns = [];
    protected $defaultDirection = 'asc';

    public function addAscColumn($column, $direction)
    {
        $this->ascColumns[$column] = $this->validateDirection($direction);
        return $this;
    }

    public function getAscColumns()
    {
        return $this->ascColumns;
    }

    public function addDescColumn($column, $direction)
    {
        $this->descColumns[$column] = $this->validateDirection($direction);
        return $this;
    }

    public function getDescColumns()
    {
        return $this->descColumns;
    }

    public function setDefaultDirection($defaultDirection)
    {
        $this->defaultDirection = $this->validateDirection($defaultDirection);
        return $this;
    }

    public function getDefaultDirection()
    {
        return $this->defaultDirection;
    }

    public function getColumns($direction)
    {
        $direction = $this->getApplicableDirection($direction);

        return
            ($direction === 'asc')
            ? $this->getAscColumns()
            : $this->getDescColumns()
        ;
    }

    public function getApplicableDirection($direction)
    {
        $direction = strtolower(trim($direction));
        $direction = ($direction === 'asc' || $direction === 'desc') ? $direction : null;
        return ($direction) ? $direction : $this->defaultDirection;
    }

    protected function validateDirection($direction)
    {
        $direction = strtolower(trim($direction));
        return ($direction === 'asc' || $direction === 'desc') ? $direction : 'asc';
    }
}