<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried\Condition;

/**
 * ConditionInterface.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
 
interface ConditionInterface
{
    public function getClause();
    public function getParams();
    public function getTypes();
    public function build(Tokenizer $tokenizer = null);
}