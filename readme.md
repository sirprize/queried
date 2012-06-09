# Queried

Reusable query component builder

## Usage

### Create a clause

    namespace MyQueried;

    use Sirprize\Queried\BaseClause;

    class ArtistClause extends BaseClause
    {
        public function build()
        {
            // these must be set before calling build()
            $alias  = $this->getAlias('release');
            $artist = $this->getArg('artist');

            // run
            $token = $this->getTokenizer()->make();
            $alias .= ($alias) ? '.' : '';

            $this
                ->setClause("{$alias}artist LIKE :$token")
                ->addParam($token, '%'.$artist.'%')
            ;

            return $this;
        }
    }

### Create a query and register clauses

Here's an example of a query built for use with the Doctrine ORM

    namespace MyQueried;

    use Sirprize\Queried\Doctrine\ORM\AbstractDoctrineQuery;

    class ReleaseQuery extends AbstractDoctrineQuery
    {
        protected $alias = 'release';
        
        public function setup()
        {
            // register the clause we built before
            $this->available = array(
                'artist' => 'MyQueried\ArtistClause'
            );
            
            // define a sorting rule
            $title = $this->getSortingRules()->newRule()
                ->addAscExpression($this->alias.'.title', 'asc')
                ->addDescExpression($this->alias.'.title', 'desc')
                ->setDefaultOrder('asc')
            ;
            
            // register sorting rule
            $this->getSortingRules()
                ->addRule('title', $title)
            ;
        }
        
        public function build()
        {
            foreach($this->active as $clause)
            {
                // build clauses
                $clause
                    ->addAlias('release', $this->alias)
                    ->setTokenizer($this->getTokenizer())
                    ->build()
                ;
                
                // get clause elements and add to query
                $this->getQueryBuilder()
                    ->andWhere($clause->getClause())
                    ->setParameters($clause->getParams(), $clause->getTypes())
                ;
            }
            
            $this->applySorting();
            
            return $this->getQueryBuilder()
                ->resetDQLPart('join')
                ->resetDQLPart('select')
                ->select($this->alias)
                ->getQuery()
            ;
        }
    }

### Use the query

    namespace MyQueried;
    
    $query = new ReleaseQuery();
    $query->activateClause('artist', array('artist' => 'rebolledo'));
    $q = $query->build();

## License

See LICENSE.
