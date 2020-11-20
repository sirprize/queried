<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */

namespace Sirprize\Queried\Exception;

use Sirprize\Queried\Exception as BaseException;

/**
 * InvalidArgumentException.
 *
 * @author Christian Hoegl <chrigu@sirprize.me>
 */

class InvalidArgumentException extends \InvalidArgumentException implements BaseException
{}