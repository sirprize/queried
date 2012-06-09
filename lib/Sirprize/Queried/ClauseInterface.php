<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried;

/**
 * ClauseInterface.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
 
interface ClauseInterface
{
    public function __construct(array $args = array());
    public function addArg($name, $value);
    public function setArgs(array $args);
    public function addAlias($name, $value);
    public function setAliases(array $aliases);
    public function setTokenizer(Tokenizer $tokenizer);
    public function getClause();
    public function getParams();
    public function getTypes();
    public function build();
}