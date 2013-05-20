<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
 
namespace Sirprize\Tests\Queried\Doctrine\ORM;

use Sirprize\Queried\Tokenizer;
use Sirprize\Queried\Doctrine\ORM\SimpleClauseClosureFactory;

class ClauseBuilderTest extends \PHPUnit_Framework_TestCase
{   
    public function testLikeClause()
    {
        $clauseFactory = new SimpleClauseClosureFactory(new Tokenizer());
        $clauseClosure = $clauseFactory->like('artist', 'release');
        $clause = $clauseClosure(array('value' => 'Rebolledo'));
        
        $this->assertInstanceOf('Sirprize\Queried\BaseClause', $clause);
        $this->assertSame('release.artist LIKE :token0', $clause->getClause());
    }

    public function testIsClause()
    {
        $clauseFactory = new SimpleClauseClosureFactory(new Tokenizer());
        $clauseClosure = $clauseFactory->is('artist', 'release');
        $clause = $clauseClosure(array('value' => 'Rebolledo'));
        
        $this->assertInstanceOf('Sirprize\Queried\BaseClause', $clause);
        $this->assertSame('release.artist = :token0', $clause->getClause());
    }

    public function testIsNotClause()
    {
        $clauseFactory = new SimpleClauseClosureFactory(new Tokenizer());
        $clauseClosure = $clauseFactory->not('artist', 'release');
        $clause = $clauseClosure(array('value' => 'Rebolledo'));
        
        $this->assertInstanceOf('Sirprize\Queried\BaseClause', $clause);
        $this->assertSame('release.artist != :token0', $clause->getClause());
    }
}