# Queried

Reusable query component builder

## Description

Parsing input, setting defaults and assembling a query can leave you writing a lot of boilerplate code. Queried helps writing reusable bits and it makes it easy to apply input as well as defaults when executing the query. 

## Usage

### Creating a clause for use in a WHERE statement

    use Sirprize\Queried\BaseClause;
    use Sirprize\Queried\Tokenizer;

    class ArtistClause extends BaseClause
    {
        public function build()
        {
            // these must be set before calling build()
            $releaseAlias  = $this->getAlias('release');
            $artist = $this->getArg('artist');

            // run
            $token = $this->getTokenizer()->make();
            $releaseAlias .= ($releaseAlias) ? '.' : '';

            $this
                ->setClause("{$releaseAlias}artist LIKE :$token")
                ->addParam($token, '%'.$artist.'%')
            ;

            return $this;
        }
    }

    $artistClause = new ArtistClause();

    $artistClause
        ->addAlias('release', 'release')
        ->addArg('artist', 'Rebolledo')
        ->setTokenizer(new Tokenizer())
        ->build()
    ;

    $clause = $artistClause->getClause(); // returns "release.artist LIKE :token0"
    $params = $artistClause->getParams(); // returns array('token0' => '%Rebolledo%')

### Defining sorting

Sorting is normally defined by two parameters: what to sort by and in which order. This is expressed by one or more field names, each with an order of either ascending or descending (eg `ORDER BY release.title ASC, release.date DESC`). The Sorting class maps rule names (eg from user input) to expressions while applying defaults in case of no input or invalid input (given a non-existing rule name) and makes sure that only valid expressions make it into the query.

    use Sirprize\Queried\Sorting\Params;
    use Sirprize\Queried\Sorting\Rules;
    use Sirprize\Queried\Sorting\Sorting;

    $rules = new Rules();
        
    $title = $rules->newRule()
        ->addAscExpression('release.title', 'asc')
        ->addDescExpression('release.title', 'desc')
        ->setDefaultOrder('asc')
    ;
    
    $date = $rules->newRule()
        ->addAscExpression('release.date', 'asc')
        ->addDescExpression('release.date', 'desc')
        ->setDefaultOrder('desc')
    ;
    
    $rules
        ->addRule('title', $title)
        ->addRule('date', $date)
    ;

    // no defaults, no parameters
    $params = new Params();
    $sorting = new Sorting($rules, $params);
    $expressions = $sorting->getExpressions(); // returns array();

    // single default
    $params = new Params();
    $params->addDefault('title', 'asc');
    $sorting = new Sorting($rules, $params);
    $expressions = $sorting->getExpressions(); // returns array('release.title' => 'asc');

    // multiple defaults
    $params = new Params();
    $params->addDefault('title', 'asc');
    $params->addDefault('date', 'asc');
    $sorting = new Sorting($rules, $params);
    $expressions = $sorting->getExpressions(); // returns array('release.title' => 'asc', 'release.date' => 'asc');

    // defaults and valid parameters
    $params = new Params();
    $params->add('date', 'asc');
    $params->addDefault('title', 'asc');
    $sorting = new Sorting($rules, $params);
    $expressions = $sorting->getExpressions(); // returns array('release.date' => 'asc');

    // no defaults and invalid parameters (non-existing rule name)
    $params = new Params();
    $params->add('xxx', 'asc');
    $sorting = new Sorting($rules, $params);
    $expressions = $sorting->getExpressions(); // returns array();

    // no defaults and invalid parameters (invalid ordering, valid orderings are "asc" or "desc")
    $params = new Params();
    $params->add('date', 'xxx');
    $sorting = new Sorting($rules, $params);
    $expressions = $sorting->getExpressions(); // returns array('release.date' => 'desc');


### Creating a query

Here's an example of a query built for use with the Doctrine ORM

    use Doctrine\ORM\QueryBuilder;
    use Sirprize\Queried\Doctrine\ORM\AbstractDoctrineQuery;

    class ReleaseQuery extends AbstractDoctrineQuery
    {
        protected $releaseAlias = 'release';
        
        public function __construct(QueryBuilder $queryBuilder)
        {
            parent::__construct($queryBuilder);

            // register the external clause we built earlier
            $this->registerClause('artist', 'ArtistClause');

            // register a simple inline clause
            $this->registerSimpleLikeClause('label', 'label', $this->releaseAlias);
            
            // define some sorting rules
            $title = $this->getSortingRules()->newRule()
                ->addAscExpression($this->releaseAlias.'.title', 'asc')
                ->addDescExpression($this->releaseAlias.'.title', 'desc')
                ->setDefaultOrder('asc')
            ;

            $artist = $this->getSortingRules()->newRule()
                ->addAscExpression($this->releaseAlias.'.artist', 'asc')
                ->addDescExpression($this->releaseAlias.'.artist', 'desc')
                ->setDefaultOrder('asc')
            ;
            
            // register sorting rule
            $this->getSortingRules()
                ->addRule('title', $title)
                ->addRule('artist', $artist)
            ;
        }
        
        public function getCountQuery()
        {
            $this->build();
            
            return $this->getQueryBuilder()
                ->select("COUNT({$this->releaseAlias}.id)")
                ->getQuery()
            ;
        }
        
        public function getFullQuery($totalItems)
        {
            $this->build();
            $this->applyRange($totalItems);
            $this->applySorting();
            
            return $this->getQueryBuilder()
                ->select($this->releaseAlias)
                ->getQuery()
            ;
        }
        
        protected function build()
        {
            if ($this->built) { return; }
            $this->built = true;
            
            $this->getQueryBuilder()
                ->from('Model\Entity\Release', $this->releaseAlias)
            ;
            
            foreach($this->getActiveClauses() as $clause)
            {
                $clause
                    ->addAlias('release', $this->releaseAlias)
                    ->setTokenizer($this->getTokenizer())
                    ->build()
                ;
                
                $this->getQueryBuilder()
                    ->andWhere($clause->getClause())
                    ->setParameters($clause->getParams(), $clause->getTypes())
                ;
            }
        }
    }

### Running the query

    use Sirprize\Paginate\Input\PageInput; // see github.com/sirprize/paginate
    use Sirprize\Paginate\Input\PageRage; // see github.com/sirprize/paginate
    use Sirprize\Queried\Sorting\Params as SortingParams;

    // pagination
    $pageInput = new PageInput($_GET['page'], $_GET['per_page']);
    $pageInput->setDefaultNumItems(25);
    $pageInput->setMaxItems(100);
    $pageRange = new PageRange($pageInput);
    
    // sorting
    $sortingParams = new SortingParams(array($_GET['sort'] => $_GET['order']));
    $sortingParams->addDefault('title', 'asc');

    // the query
    $query = new ReleaseQuery($em->createQueryBuilder()); // $em = the Doctrine entity manager

    $query
        ->activateClause('artist', array('artist' => $_GET['artist']))
        ->setSortingParams($sortingParams)
        ->setRange($range)
    ;

    $count = $query->getCountQuery()->getSingleResult();
    $releases = $query->getFullQuery($count[1])->getResult();

## License

See LICENSE.
