# Queried

Database/ORM-agnostic query construction helper

## Description

Parsing input, setting defaults and constructing `SELECT` queries can quickly become messy. Queried helps organizing  clauses for `WHERE`, `HAVING` and `ORDER BY`, making it easy to apply input as well as defaults when executing the query.

## Usage

### Creating and activating simple WHERE conditions

First we'll take a look at organizing conditions for the `WHERE` and `HAVING` parts of a query statement. The basic idea is to prepare clauses describing specific conditions, register them with a query object and then activate them individually based on application requirements and user input. Each clause is wrapped in a `BaseCondition` object. Those objects are then registered with a `BaseQuery` object:

    use Sirprize\Queried\BaseQuery;
    use Sirprize\Queried\Condition\BaseCondition;

    $publishedCondition = new BaseCondition();
    $publishedCondition->setClause("(release.date <= CURRENT_DATE() AND release.published = 1)");

    $physicalCondition = new BaseCondition();
    $physicalCondition->setClause("(release.format = 'LP' OR release.format = 'CD'");

    $digitalCondition = new BaseCondition();
    $digitalCondition->setClause("(release.format = 'MP3' OR release.format = 'WAV'");

    $query = new BaseQuery();

    $query
        ->registerCondition('published', $publishedCondition)
        ->registerCondition('physical', $physicalCondition)
        ->registerCondition('digital', $physicalCondition)
    ;

Next we'll activate some of the conditions according application requirements. Only published releases with a release-date of today or older must make it into the result:

    $query->activateCondition('published');

Then the user can choose from digital or physical releases by setting the `format` parameter (eg `/releases?format=digital`)

    $format = (array_key_exists('format', $_GET)) ? $_GET['format'] : null;

    if ($format === 'digital') {
        $query->activateCondition('digital');
    }
    else {
        $query->activateCondition('physical');
    }

And finally we'll collect the activated conditions and add them to our query statement:

    foreach($query->getActiveConditions() as $condition)
    {
        $clause = $condition->getClause();
        // Add clause to query
    }

### More complex WHERE conditions

For more complex conditions we'll subclass `BaseCondition`. This allows us to share conditions between queries. It's also the right place to set sensible defaults in case of invalid input:

    use Sirprize\Queried\Condition\BaseCondition;
    use Sirprize\Queried\Condition\Tokenizer;

    class ArtistCondition extends BaseCondition
    {
        protected $alias = null;

        public function __construct($alias = '')
        {
            $this->alias = $alias;
        }

        public function build(Tokenizer $tokenizer = null)
        {
            $artist = $this->getValue('artist');

            if (!$artist)
            {
                return $this;
            }

            $token = $tokenizer->make();
            $alias = ($this->alias) ? $this->alias . '.' : $this->alias;

            $this
                ->setClause("{$alias}artist LIKE :$token")
                ->addParam($token, '%'.$artist.'%')
            ;

            return $this;
        }
    }

Note the `$tokenizer` parameter in the `build()` method - this is a simple object with an internal counter, letting us define non-conflicting parameter names. This is useful when combining many `BaseCondition` objects into one query.

Next we'll instantiate and build the condition, providing values from user input:
    
    $artist = (array_key_exists('artist', $_GET)) ? $_GET['artist'] : null;
    $artistCondition = new ArtistCondition('release');

    $artistCondition
        ->addValue('artist', $artist)
        ->build(new Tokenizer())
    ;

And when it's time to assemble the query, we have the final clause plus parameters available:

    $condition = $artistCondition->getClause(); // "release.artist LIKE :token0"
    $params = $artistCondition->getParams(); // array('token0' => '%Rebolledo%')

### Defining sorting

Sorting is normally expressed by one or more field names, each with a direction of either ascending or descending (eg `ORDER BY release.date DESC, release.title ASC`). This information is stored in a `Rule` object, both for ascending and descending order:

    use Sirprize\Queried\Sorting\Rule;
    
    $dateRule = new Rule();

    $dateRule
        ->addAscColumn('release.date', 'desc')
        ->addAscColumn('release.date', 'asc')
        ->addDescColumn('release.date', 'asc')
        ->addDescColumn('release.date', 'asc')
        ->setDefaultOrder('asc')
    ;

    $columns = $dateRule->getAscColumns(); // array('release.date' => 'desc', 'release.title.asc')
    $columns = $dateRule->getDescColumns(); // array('release.date' => 'asc', 'release.title.asc')

The Sorting class maps rule names (eg from user input) to rules while applying defaults, given a non-existing rule name. It makes sure that only valid column definitions make it into the query. Let's put it all together:

    use Sirprize\Queried\Sorting\Input;
    use Sirprize\Queried\Sorting\Rules;
    use Sirprize\Queried\Sorting\Sorting;

    $rules = new Rules();
    
    $rules->newRule('title')
        ->addAscColumn('release.title', 'asc')
        ->addDescColumn('release.title', 'desc')
        ->setDefaultOrder('asc')
    ;
    
    $rules->newRule('date')
        ->addAscColumn('release.date', 'asc')
        ->addDescColumn('release.date', 'desc')
        ->setDefaultOrder('desc')
    ;

No defaults, no parameters

    $input = new Input();
    $sorting = new Sorting($rules, $input);
    $columns = $sorting->getColumns(); // array();

Single default

    $input = new Input();
    $input->addDefault('title', 'asc');
    $sorting = new Sorting($rules, $input);
    $columns = $sorting->getColumns(); // array('release.title' => 'asc');

Multiple defaults

    $input = new Input();
    $input->addDefault('title', 'asc');
    $input->addDefault('date', 'asc');
    $sorting = new Sorting($rules, $input);
    $columns = $sorting->getColumns(); // array('release.title' => 'asc', 'release.date' => 'asc');

Defaults and valid parameters

    $input = new Input();
    $input->add('date', 'asc');
    $input->addDefault('title', 'asc');
    $sorting = new Sorting($rules, $input);
    $columns = $sorting->getColumns(); // array('release.date' => 'asc');

No defaults and invalid parameters (non-existing rule name)

    $input = new Input();
    $input->add('xxx', 'asc');
    $sorting = new Sorting($rules, $input);
    $columns = $sorting->getColumns(); // array();

No defaults and invalid parameters (invalid ordering, valid orderings are "asc" or "desc")

    $input = new Input();
    $input->add('date', 'xxx');
    $sorting = new Sorting($rules, $input);
    $columns = $sorting->getColumns(); // array('release.date' => 'desc');


### Putting it all together

It's best to manage the construction of the entire query in a subclass of `BaseQuery`. Here's an example of a query built for use with the Doctrine ORM - it's a subclass of `AbstractDoctrineQuery` which in turn is a subclass `BaseQuery` and provides some utility methods for query construction:

    namespace My\Model\Query;

    use Doctrine\ORM\QueryBuilder;
    use Sirprize\Queried\Doctrine\ORM\AbstractDoctrineQuery;

    class ReleaseQuery extends AbstractDoctrineQuery
    {
        protected $releaseAlias = 'release';
        protected $fromApplied = false;
        protected $conditionsApplied = false;
        
        public function __construct(QueryBuilder $queryBuilder)
        {
            parent::__construct($queryBuilder);

            // register the external condition we built earlier
            $this->registerCondition('artist', new ArtistCondition($this->releaseAlias));

            // register a simple inline condition
            $this->registerSimpleLikeCondition('label', 'label', $this->releaseAlias);

            // register another inline condition
            $pc = new BaseCondition();
            $pc->setClause("({$this->releaseAlias}.date <= CURRENT_DATE() AND {$this->releaseAlias}.published = 1)");
            $this->registerCondition('published', $pc);
            
            // define some sorting rules
            $this->getSortingRules()->newRule('title')
                ->addAscColumn($this->releaseAlias.'.title', 'asc')
                ->addDescColumn($this->releaseAlias.'.title', 'desc')
                ->setDefaultOrder('asc')
            ;

            $this->getSortingRules()->newRule('artist')
                ->addAscColumn($this->releaseAlias.'.artist', 'asc')
                ->addDescColumn($this->releaseAlias.'.artist', 'desc')
                ->setDefaultOrder('asc')
            ;
        }
        
        public function getCountQuery()
        {
            $this->applyFrom();
            $this->applyConditions();
            
            return $this->getQueryBuilder()
                ->resetDQLPart('select')
                ->select("COUNT({$this->releaseAlias}.id)")
                ->getQuery()
            ;
        }
        
        public function getPaginatedQuery($totalItems)
        {
            $this->applyFrom();
            $this->applyConditions();
            $this->applyRange($totalItems);
            $this->applySorting();
            
            return $this->getQueryBuilder()
                ->resetDQLPart('select')
                ->select($this->releaseAlias)
                ->getQuery()
            ;
        }

        protected function applyFrom()
        {
            if ($this->fromApplied)
            {
                return;
            }

            $this->fromApplied = true;

            $this->getQueryBuilder()
                ->from('My\Model\Entity\Product', $this->releaseAlias)
            ;
        }

        protected function applyConditions()
        {
            if ($this->conditionsApplied)
            {
                return;
            }

            $this->conditionsApplied = true;

            foreach($this->getActiveConditions() as $condition)
            {
                $condition->build($this->getTokenizer());
                
                if (!$condition->getClause())
                {
                    continue;
                }

                $this->getQueryBuilder()->andWhere($condition->getClause());

                foreach($condition->getParams() as $name => $value)
                {
                    $this->getQueryBuilder()->setParameter($name, $value, $condition->getType($name));
                }
            }
        }
    }

### Running the query

    use Sirprize\Paginate\Input\PageInput; // see github.com/sirprize/paginate
    use Sirprize\Paginate\Input\PageRage; // see github.com/sirprize/paginate
    use Sirprize\Queried\Sorting\Input as SortingInput;
    use My\Model\Query\ReleaseQuery;

    // user input
    $perPage = (array_key_exists('per_page', $_GET)) ? $_GET['per_page'] : null;
    $page = (array_key_exists('page', $_GET)) ? $_GET['page'] : null;
    $sort = (array_key_exists('sort', $_GET)) ? $_GET['sort'] : null;
    $order = (array_key_exists('order', $_GET)) ? $_GET['order'] : null;
    $label = (array_key_exists('label', $_GET)) ? $_GET['label'] : null;
    $artist = (array_key_exists('artist', $_GET)) ? $_GET['artist'] : null;

    // pagination
    $pageInput = new PageInput($page, $perPage);
    $pageInput->setDefaultNumItems(25);
    $pageInput->setMaxItems(100);
    $pageRange = new PageRange($pageInput);
    
    // sorting
    $sortingInput = new SortingInput(array($sort => $order));
    $sortingInput->addDefault('title', 'asc');

    // the query
    $query = new ReleaseQuery($em->createQueryBuilder());

    $query
        ->activateCondition('published')
        ->activateCondition('label', array('label' => $label))
        ->activateCondition('artist', array('artist' => $artist))
        ->setSortingInput($sortingInput)
        ->setRange($range)
    ;

    $count = $query->getCountQuery()->getSingleResult();
    $releases = $query->getFullQuery($count[1])->getResult();

## License

See LICENSE.