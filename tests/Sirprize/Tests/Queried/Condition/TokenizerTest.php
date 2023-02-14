<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\Condition;

use Sirprize\Queried\Condition\Tokenizer;
use PHPUnit\Framework\TestCase;

class TokenizerTest extends TestCase
{
    public function testTokenizer()
    {
        $tokenizer = new Tokenizer('t');
        $this->assertSame('t0', $tokenizer->make());
        $this->assertSame('t1', $tokenizer->make());
        $this->assertSame('t2', $tokenizer->make());
    }
}