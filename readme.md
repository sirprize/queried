# Queried

Database/ORM-agnostic query construction helper

## Description

Parsing input, setting defaults and constructing `SELECT` queries can quickly become a mess. Queried helps organizing clauses for `WHERE`, `HAVING` and `ORDER BY`, making it easy to apply input as well as defaults when executing the query.

## Usage

### Creating and activating simple WHERE conditions

First we'll take a look at organizing conditions for the `WHERE` and `HAVING` parts of a query statement. The basic idea is to prepare clauses describing specific conditions, register them with a query object and then activate them individually based on application requirements and user input. Each clause is wrapped in a `BaseCondition` object. Those objects are then registered with a `BaseQueryConfigurator` object:

    use Sirprize\Queried\BaseQueryConfigurator;
    use Sirprize\Queried\Condition\BaseCondition;

    $publishedCondition = new BaseCondition();
    $publishedCondition->setClause("(release.date <= CURRENT_DATE() AND release.published = 1)");

    $physicalCondition = new BaseCondition();
    $physicalCondition->setClause("(release.format = 'LP' OR release.format = 'CD'");

    $digitalCondition = new BaseCondition();
    $digitalCondition->setClause("(release.format = 'MP3' OR release.format = 'WAV'");

    $queryConfigurator = new BaseQueryConfigurator();

    $queryConfigurator->getConditionRegistry()
        ->registerCondition('published', $publishedCondition)
        ->registerCondition('physical', $physicalCondition)
        ->registerCondition('digital', $physicalCondition)
    ;

Next we'll activate some of the conditions according application requirements. Only published releases with a release-date of today or older must make it into the result:

    $queryConfigurator->getConditionRegistry()->activateCondition('published');

Then the user can choose from digital or physical releases by setting the `format` parameter (eg `/releases?format=digital`)

    $format = (array_key_exists('format', $_GET)) ? $_GET['format'] : null;

    if ($format === 'digital')
    {
        $queryConfigurator->getConditionRegistry()->activateCondition('digital');
    }
    else {
        $queryConfigurator->getConditionRegistry()->activateCondition('physical');
    }

And finally we'll collect the activated conditions and add them to our query statement:

    foreach ($queryConfigurator->getActiveConditions() as $condition)
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
            $this->reset();

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
        ->setDefaultDirection('asc')
    ;

    $columns = $dateRule->getAscColumns(); // array('release.date' => 'desc', 'release.title.asc')
    $columns = $dateRule->getDescColumns(); // array('release.date' => 'asc', 'release.title.asc')

The Sorting class maps rule names (eg from user input) to rules while applying defaults, given a non-existing rule name. It makes sure that only valid column definitions make it into the query. Let's put it all together:

    use Sirprize\Queried\Sorting\Params;
    use Sirprize\Queried\Sorting\Rules;
    use Sirprize\Queried\Sorting\Sorting;

    $rules = new Rules();
    
    $rules->newRule('title')
        ->addAscColumn('release.title', 'asc')
        ->addDescColumn('release.title', 'desc')
        ->setDefaultDirection('asc')
    ;
    
    $rules->newRule('date')
        ->addAscColumn('release.date', 'asc')
        ->addDescColumn('release.date', 'desc')
        ->setDefaultDirection('desc')
    ;

No defaults, no parameters

    $params = new Params();
    $sorting = new Sorting();
    $sorting->setRules($rules);
    $sorting->setParams($params);
    $columns = $sorting->getColumns(); // array();

Single default

    $defaults = new Params('title', 'asc');
    $sorting = new Sorting();
    $sorting->setRules($rules);
    $sorting->setDefaults($defaults);
    $columns = $sorting->getColumns(); // array('release.title' => 'asc');

Defaults and valid parameters

    $params = new Params('date', 'asc');
    $defaults = new Params('title', 'asc');
    $sorting = new Sorting();
    $sorting->setRules($rules);
    $sorting->setParams($params);
    $sorting->setDefaults($defaults);
    $columns = $sorting->getColumns(); // array('release.date' => 'asc');

No defaults and invalid parameters (non-existing rule name)

    $params = new Params('xxx', 'asc');
    $sorting = new Sorting();
    $sorting->setRules($rules);
    $sorting->setParams($params);
    $columns = $sorting->getColumns(); // array();

No defaults and invalid parameters (invalid ordering, valid orderings are "asc" or "desc")

    $params = new Params('date', 'xxx');
    $sorting = new Sorting();
    $sorting->setRules($rules);
    $sorting->setParams($params);
    $columns = $sorting->getColumns(); // array('release.date' => 'desc');

### Putting it all together

It's best to manage the construction of the entire query in a subclass of `BaseQueryConfigurator`. Here's an example of a query built for use with the Doctrine ORM:

    use Doctrine\ORM\EntityManager;
    use Sirprize\Queried\BaseQueryConfigurator;

    class ReleaseQueryConfigurator extends BaseQueryConfigurator
    {
        protected $queryBuilder = null;
        protected $releaseAlias = 'release';

        public function __construct(EntityManager $entityManager)
        {
            $this->queryBuilder = $entityManager->createQueryBuilder();

            // register the external condition we built earlier
            $this->getConditionRegistry()->registerCondition('artist', new ArtistCondition($this->releaseAlias));

            // register an inline condition
            $pc = new BaseCondition();
            $pc->setClause("({$this->releaseAlias}.date <= CURRENT_DATE() AND {$this->releaseAlias}.published = 1)");
            $this->getConditionRegistry()->registerCondition('published', $pc);
            
            // define some sorting rules
            $this->getSorting()->getRules()->newRule('title')
                ->addAscColumn($this->releaseAlias.'.title', 'asc')
                ->addDescColumn($this->releaseAlias.'.title', 'desc')
                ->setDefaultDirection('asc')
            ;

            $this->getSorting()->getRules()->newRule('artist')
                ->addAscColumn($this->releaseAlias.'.artist', 'asc')
                ->addDescColumn($this->releaseAlias.'.artist', 'desc')
                ->setDefaultDirection('asc')
            ;
        }

        public function getCountQuery()
        {
            $this->reset();
            $this->applyFrom();
            $this->applyConditions();
            
            return $this->queryBuilder
                ->select("COUNT({$this->releaseAlias}.id)")
                ->getQuery()
            ;
        }
        
        public function getQuery()
        {
            $this->reset();
            $this->applyFrom();
            $this->applyConditions();
            $this->applySorting();

            return $this->queryBuilder
                ->select($this->releaseAlias)
                ->getQuery()
            ;
        }

        protected function applyFrom()
        {
            $this->queryBuilder
                ->from('My\Model\Entity\Product', $this->releaseAlias)
            ;
        }

        public function reset()
        {
            $this->queryBuilder
                ->resetDQLParts()
                ->setParameters(new ArrayCollection())
            ;
        }

        public function applySorting()
        {
            foreach ($this->getSorting()->getColumns() as $column => $order)
            {
                $this->queryBuilder->addOrderBy($column, $order);
            }
        }

        protected function applyConditions()
        {
            foreach ($this->getConditionRegistry()->getActiveConditions() as $condition)
            {
                $condition->build($this->getTokenizer());

                if (!$condition->getClause())
                {
                    continue;
                }

                $this->queryBuilder->andWhere($condition->getClause());

                foreach ($condition->getParams() as $name => $value)
                {
                    $this->queryBuilder->setParameter($name, $value, $condition->getType($name));
                }
            }
        }
    }

### Running the query

    use Sirprize\Queried\Sorting\Params as SortingParams;

    // input
    $sort = (array_key_exists('sort', $_GET)) ? $_GET['sort'] : null;
    $order = (array_key_exists('order', $_GET)) ? $_GET['order'] : null;
    $label = (array_key_exists('label', $_GET)) ? $_GET['label'] : null;
    $artist = (array_key_exists('artist', $_GET)) ? $_GET['artist'] : null;

    // sorting
    $sortingParams = new SortingParams($sort, $order);
    $sortingDefaults = new SortingParams('title', 'asc');

    // the query
    $queryConfigurator = new ReleaseQueryConfigurator($em);

    $queryConfigurator
        ->activateCondition('published')
        ->activateCondition('artist', array('artist' => $artist))
    ;

    $queryConfigurator->getSorting()->setParams($sortingParams);
    $queryConfigurator->getSorting()->setDefaults($sortingDefaults);

    $count = $queryConfigurator->getCountQuery()->getSingleResult();
    $releases = $queryConfigurator->getQuery()->getResult();

## License

See LICENSE.