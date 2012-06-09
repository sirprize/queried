<?php

/*
 * This file is part of the Queried package
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 */
namespace Sirprize\Tests\Queried\UseCaseQueryWithExternalClauses;

use Sirprize\Queried\BaseClause;

class LabelClause extends BaseClause
{
    
    public function build()
    {
        // these must be set before calling build()
        $alias  = $this->getAlias('release');
        $label = $this->getArg('label');
        
        // run
        $token = $this->getTokenizer()->make();
        $alias .= ($alias) ? '.' : '';

        $this
            ->setClause("{$alias}label = :$token")
            ->addParam($token, $label)
        ;
        
        return $this;
    }
}