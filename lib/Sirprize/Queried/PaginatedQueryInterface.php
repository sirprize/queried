<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Queried;

/**
 * PaginatedQueryInterface.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */
 
interface PaginatedQueryInterface
{
    public function getCountQuery();
    public function getFullQuery($totalItems);
}