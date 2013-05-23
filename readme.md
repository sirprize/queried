# Queried

Reusable query component builder

## Description

Parsing input, setting defaults and assembling `SELECT` queries can leave you writing a lot of boilerplate code. Queried helps writing reusable conditions for `WHERE` and `ORDER BY` and it makes it easy to apply input as well as defaults when executing the query. Queried can be used with any database or ORM. 

## Usage

### Creating a condition for use in a WHERE statement

    use Sirprize\Queried\Where\BaseCondition;
    use Sirprize\Queried\Where\Tokenizer;

    class ArtistCondition extends BaseCondition
    {
        public function build()
        {
            // these must be set before calling build()
            $releaseAlias  = $this->getAlias('release');
            $artist = $this->getValue('artist');

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

    $artistCondition = new ArtistCondition();

    $artistCondition
        ->addAlias('release', 'release')
        ->addValue('artist', 'Rebolledo')
        ->setTokenizer(new Tokenizer())
        ->build()
    ;

    $condition = $artistCondition->getClause(); // returns "release.artist LIKE :token0"
    $params = $artistCondition->getParams(); // returns array('token0' => '%Rebolledo%')

### Defining sorting

Sorting is normally defined by two parameters: what to sort by and in which direction. This is expressed by one or more field names, each with an direction of either ascending or descending (eg `ORDER BY release.date DESC, release.title ASC`). The Sorting class maps rule names (eg from user input) to columns while applying defaults in case of no input or invalid input (given a non-existing rule name) and makes sure that only valid columns make it into the query.

    use Sirprize\Queried\Sorting\Params;
    use Sirprize\Queried\Sorting\Rules;
    use Sirprize\Queried\Sorting\Sorting;

    // the rules container
    $rules = new Rules();
    
    // add rules
    $title = $rules->newRule()
        ->addAscColumn('release.title', 'asc')
        ->addDescColumn('release.title', 'desc')
        ->setDefaultOrder('asc')
    ;
    
    $date = $rules->newRule()
        ->addAscColumn('release.date', 'asc')
        ->addDescColumn('release.date', 'desc')
        ->setDefaultOrder('desc')
    ;
    
    $rules
        ->addRule('title', $title)
        ->addRule('date', $date)
    ;

    // no defaults, no parameters
    $params = new Params();
    $sorting = new Sorting($rules, $params);
    $columns = $sorting->getColumns(); // returns array();

    // single default
    $params = new Params();
    $params->addDefault('title', 'asc');
    $sorting = new Sorting($rules, $params);
    $columns = $sorting->getColumns(); // returns array('release.title' => 'asc');

    // multiple defaults
    $params = new Params();
    $params->addDefault('title', 'asc');
    $params->addDefault('date', 'asc');
    $sorting = new Sorting($rules, $params);
    $columns = $sorting->getColumns(); // returns array('release.title' => 'asc', 'release.date' => 'asc');

    // defaults and valid parameters
    $params = new Params();
    $params->add('date', 'asc');
    $params->addDefault('title', 'asc');
    $sorting = new Sorting($rules, $params);
    $columns = $sorting->getColumns(); // returns array('release.date' => 'asc');

    // no defaults and invalid parameters (non-existing rule name)
    $params = new Params();
    $params->add('xxx', 'asc');
    $sorting = new Sorting($rules, $params);
    $columns = $sorting->getColumns(); // returns array();

    // no defaults and invalid parameters (invalid ordering, valid orderings are "asc" or "desc")
    $params = new Params();
    $params->add('date', 'xxx');
    $sorting = new Sorting($rules, $params);
    $columns = $sorting->getColumns(); // returns array('release.date' => 'desc');


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

            // register the external condition we built earlier
            $this->registerCondition('artist', 'ArtistCondition');

            // register a simple inline condition
            $this->registerSimpleLikeCondition('label', 'label', $this->releaseAlias);
            
            // define some sorting rules
            $title = $this->getSortingRules()->newRule()
                ->addAscColumn($this->releaseAlias.'.title', 'asc')
                ->addDescColumn($this->releaseAlias.'.title', 'desc')
                ->setDefaultOrder('asc')
            ;

            $artist = $this->getSortingRules()->newRule()
                ->addAscColumn($this->releaseAlias.'.artist', 'asc')
                ->addDescColumn($this->releaseAlias.'.artist', 'desc')
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
            
            foreach($this->getActiveConditions() as $condition)
            {
                $condition
                    ->addAlias('release', $this->releaseAlias)
                    ->setTokenizer($this->getTokenizer())
                    ->build()
                ;
                
                $this->getQueryBuilder()
                    ->andWhere($condition->getClause())
                    ->setParameters($condition->getParams(), $condition->getTypes())
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
        ->activateCondition('artist', array('artist' => $_GET['artist']))
        ->setSortingParams($sortingParams)
        ->setRange($range)
    ;

    $count = $query->getCountQuery()->getSingleResult();
    $releases = $query->getFullQuery($count[1])->getResult();

## License

See LICENSE.
