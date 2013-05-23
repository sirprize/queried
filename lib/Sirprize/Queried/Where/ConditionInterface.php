<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried\Where;

/**
 * ConditionInterface.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
 
interface ConditionInterface
{
    public function __construct(array $values = array(), array $aliases = array(), array $types = array());
    #public function addValue($name, $value);
    #public function setValues(array $values);
    #public function addAlias($name, $value);
    #public function setAliases(array $aliases);
    public function getClause();
    public function getParams();
    public function getTypes();
    public function build();
}