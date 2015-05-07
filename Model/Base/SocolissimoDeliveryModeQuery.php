<?php

namespace SoColissimo\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use SoColissimo\Model\SocolissimoDeliveryMode as ChildSocolissimoDeliveryMode;
use SoColissimo\Model\SocolissimoDeliveryModeQuery as ChildSocolissimoDeliveryModeQuery;
use SoColissimo\Model\Map\SocolissimoDeliveryModeTableMap;

/**
 * Base class that represents a query for the 'socolissimo_delivery_mode' table.
 *
 *
 *
 * @method     ChildSocolissimoDeliveryModeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildSocolissimoDeliveryModeQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildSocolissimoDeliveryModeQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method     ChildSocolissimoDeliveryModeQuery orderByFreeshippingActive($order = Criteria::ASC) Order by the freeshipping_active column
 * @method     ChildSocolissimoDeliveryModeQuery orderByFreeshippingFrom($order = Criteria::ASC) Order by the freeshipping_from column
 *
 * @method     ChildSocolissimoDeliveryModeQuery groupById() Group by the id column
 * @method     ChildSocolissimoDeliveryModeQuery groupByTitle() Group by the title column
 * @method     ChildSocolissimoDeliveryModeQuery groupByCode() Group by the code column
 * @method     ChildSocolissimoDeliveryModeQuery groupByFreeshippingActive() Group by the freeshipping_active column
 * @method     ChildSocolissimoDeliveryModeQuery groupByFreeshippingFrom() Group by the freeshipping_from column
 *
 * @method     ChildSocolissimoDeliveryModeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSocolissimoDeliveryModeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSocolissimoDeliveryModeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSocolissimoDeliveryModeQuery leftJoinSocolissimoPrice($relationAlias = null) Adds a LEFT JOIN clause to the query using the SocolissimoPrice relation
 * @method     ChildSocolissimoDeliveryModeQuery rightJoinSocolissimoPrice($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SocolissimoPrice relation
 * @method     ChildSocolissimoDeliveryModeQuery innerJoinSocolissimoPrice($relationAlias = null) Adds a INNER JOIN clause to the query using the SocolissimoPrice relation
 *
 * @method     ChildSocolissimoDeliveryMode findOne(ConnectionInterface $con = null) Return the first ChildSocolissimoDeliveryMode matching the query
 * @method     ChildSocolissimoDeliveryMode findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSocolissimoDeliveryMode matching the query, or a new ChildSocolissimoDeliveryMode object populated from the query conditions when no match is found
 *
 * @method     ChildSocolissimoDeliveryMode findOneById(int $id) Return the first ChildSocolissimoDeliveryMode filtered by the id column
 * @method     ChildSocolissimoDeliveryMode findOneByTitle(string $title) Return the first ChildSocolissimoDeliveryMode filtered by the title column
 * @method     ChildSocolissimoDeliveryMode findOneByCode(string $code) Return the first ChildSocolissimoDeliveryMode filtered by the code column
 * @method     ChildSocolissimoDeliveryMode findOneByFreeshippingActive(boolean $freeshipping_active) Return the first ChildSocolissimoDeliveryMode filtered by the freeshipping_active column
 * @method     ChildSocolissimoDeliveryMode findOneByFreeshippingFrom(double $freeshipping_from) Return the first ChildSocolissimoDeliveryMode filtered by the freeshipping_from column
 *
 * @method     array findById(int $id) Return ChildSocolissimoDeliveryMode objects filtered by the id column
 * @method     array findByTitle(string $title) Return ChildSocolissimoDeliveryMode objects filtered by the title column
 * @method     array findByCode(string $code) Return ChildSocolissimoDeliveryMode objects filtered by the code column
 * @method     array findByFreeshippingActive(boolean $freeshipping_active) Return ChildSocolissimoDeliveryMode objects filtered by the freeshipping_active column
 * @method     array findByFreeshippingFrom(double $freeshipping_from) Return ChildSocolissimoDeliveryMode objects filtered by the freeshipping_from column
 *
 */
abstract class SocolissimoDeliveryModeQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \SoColissimo\Model\Base\SocolissimoDeliveryModeQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\SoColissimo\\Model\\SocolissimoDeliveryMode', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSocolissimoDeliveryModeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSocolissimoDeliveryModeQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \SoColissimo\Model\SocolissimoDeliveryModeQuery) {
            return $criteria;
        }
        $query = new \SoColissimo\Model\SocolissimoDeliveryModeQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildSocolissimoDeliveryMode|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SocolissimoDeliveryModeTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SocolissimoDeliveryModeTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildSocolissimoDeliveryMode A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, TITLE, CODE, FREESHIPPING_ACTIVE, FREESHIPPING_FROM FROM socolissimo_delivery_mode WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildSocolissimoDeliveryMode();
            $obj->hydrate($row);
            SocolissimoDeliveryModeTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildSocolissimoDeliveryMode|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildSocolissimoDeliveryModeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SocolissimoDeliveryModeTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildSocolissimoDeliveryModeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SocolissimoDeliveryModeTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSocolissimoDeliveryModeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SocolissimoDeliveryModeTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SocolissimoDeliveryModeTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SocolissimoDeliveryModeTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSocolissimoDeliveryModeQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SocolissimoDeliveryModeTableMap::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE code = 'fooValue'
     * $query->filterByCode('%fooValue%'); // WHERE code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $code The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSocolissimoDeliveryModeQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $code)) {
                $code = str_replace('*', '%', $code);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SocolissimoDeliveryModeTableMap::CODE, $code, $comparison);
    }

    /**
     * Filter the query on the freeshipping_active column
     *
     * Example usage:
     * <code>
     * $query->filterByFreeshippingActive(true); // WHERE freeshipping_active = true
     * $query->filterByFreeshippingActive('yes'); // WHERE freeshipping_active = true
     * </code>
     *
     * @param     boolean|string $freeshippingActive The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSocolissimoDeliveryModeQuery The current query, for fluid interface
     */
    public function filterByFreeshippingActive($freeshippingActive = null, $comparison = null)
    {
        if (is_string($freeshippingActive)) {
            $freeshipping_active = in_array(strtolower($freeshippingActive), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SocolissimoDeliveryModeTableMap::FREESHIPPING_ACTIVE, $freeshippingActive, $comparison);
    }

    /**
     * Filter the query on the freeshipping_from column
     *
     * Example usage:
     * <code>
     * $query->filterByFreeshippingFrom(1234); // WHERE freeshipping_from = 1234
     * $query->filterByFreeshippingFrom(array(12, 34)); // WHERE freeshipping_from IN (12, 34)
     * $query->filterByFreeshippingFrom(array('min' => 12)); // WHERE freeshipping_from > 12
     * </code>
     *
     * @param     mixed $freeshippingFrom The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSocolissimoDeliveryModeQuery The current query, for fluid interface
     */
    public function filterByFreeshippingFrom($freeshippingFrom = null, $comparison = null)
    {
        if (is_array($freeshippingFrom)) {
            $useMinMax = false;
            if (isset($freeshippingFrom['min'])) {
                $this->addUsingAlias(SocolissimoDeliveryModeTableMap::FREESHIPPING_FROM, $freeshippingFrom['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($freeshippingFrom['max'])) {
                $this->addUsingAlias(SocolissimoDeliveryModeTableMap::FREESHIPPING_FROM, $freeshippingFrom['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SocolissimoDeliveryModeTableMap::FREESHIPPING_FROM, $freeshippingFrom, $comparison);
    }

    /**
     * Filter the query by a related \SoColissimo\Model\SocolissimoPrice object
     *
     * @param \SoColissimo\Model\SocolissimoPrice|ObjectCollection $socolissimoPrice  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSocolissimoDeliveryModeQuery The current query, for fluid interface
     */
    public function filterBySocolissimoPrice($socolissimoPrice, $comparison = null)
    {
        if ($socolissimoPrice instanceof \SoColissimo\Model\SocolissimoPrice) {
            return $this
                ->addUsingAlias(SocolissimoDeliveryModeTableMap::ID, $socolissimoPrice->getDeliveryModeId(), $comparison);
        } elseif ($socolissimoPrice instanceof ObjectCollection) {
            return $this
                ->useSocolissimoPriceQuery()
                ->filterByPrimaryKeys($socolissimoPrice->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySocolissimoPrice() only accepts arguments of type \SoColissimo\Model\SocolissimoPrice or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SocolissimoPrice relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildSocolissimoDeliveryModeQuery The current query, for fluid interface
     */
    public function joinSocolissimoPrice($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SocolissimoPrice');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'SocolissimoPrice');
        }

        return $this;
    }

    /**
     * Use the SocolissimoPrice relation SocolissimoPrice object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \SoColissimo\Model\SocolissimoPriceQuery A secondary query class using the current class as primary query
     */
    public function useSocolissimoPriceQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSocolissimoPrice($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SocolissimoPrice', '\SoColissimo\Model\SocolissimoPriceQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSocolissimoDeliveryMode $socolissimoDeliveryMode Object to remove from the list of results
     *
     * @return ChildSocolissimoDeliveryModeQuery The current query, for fluid interface
     */
    public function prune($socolissimoDeliveryMode = null)
    {
        if ($socolissimoDeliveryMode) {
            $this->addUsingAlias(SocolissimoDeliveryModeTableMap::ID, $socolissimoDeliveryMode->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the socolissimo_delivery_mode table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SocolissimoDeliveryModeTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SocolissimoDeliveryModeTableMap::clearInstancePool();
            SocolissimoDeliveryModeTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildSocolissimoDeliveryMode or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildSocolissimoDeliveryMode object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SocolissimoDeliveryModeTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SocolissimoDeliveryModeTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        SocolissimoDeliveryModeTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SocolissimoDeliveryModeTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // SocolissimoDeliveryModeQuery
