<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\Where;

use Sirprize\Queried\Where\Tokenizer;

class TokenizerTest extends \PHPUnit_Framework_TestCase
{
    public function testTokenizer()
    {
        $tokenizer = new Tokenizer('t');
        $this->assertSame('t0', $tokenizer->make());
        $this->assertSame('t1', $tokenizer->make());
        $this->assertSame('t2', $tokenizer->make());
    }
}