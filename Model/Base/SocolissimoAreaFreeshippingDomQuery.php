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
use SoColissimo\Model\SocolissimoAreaFreeshippingDom as ChildSocolissimoAreaFreeshippingDom;
use SoColissimo\Model\SocolissimoAreaFreeshippingDomQuery as ChildSocolissimoAreaFreeshippingDomQuery;
use SoColissimo\Model\Map\SocolissimoAreaFreeshippingDomTableMap;
use SoColissimo\Model\Thelia\Model\Area;

/**
 * Base class that represents a query for the 'socolissimo_area_freeshipping_dom' table.
 *
 *
 *
 * @method     ChildSocolissimoAreaFreeshippingDomQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildSocolissimoAreaFreeshippingDomQuery orderByAreaId($order = Criteria::ASC) Order by the area_id column
 * @method     ChildSocolissimoAreaFreeshippingDomQuery orderByDeliveryModeId($order = Criteria::ASC) Order by the delivery_mode_id column
 * @method     ChildSocolissimoAreaFreeshippingDomQuery orderByCartAmount($order = Criteria::ASC) Order by the cart_amount column
 *
 * @method     ChildSocolissimoAreaFreeshippingDomQuery groupById() Group by the id column
 * @method     ChildSocolissimoAreaFreeshippingDomQuery groupByAreaId() Group by the area_id column
 * @method     ChildSocolissimoAreaFreeshippingDomQuery groupByDeliveryModeId() Group by the delivery_mode_id column
 * @method     ChildSocolissimoAreaFreeshippingDomQuery groupByCartAmount() Group by the cart_amount column
 *
 * @method     ChildSocolissimoAreaFreeshippingDomQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildSocolissimoAreaFreeshippingDomQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildSocolissimoAreaFreeshippingDomQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildSocolissimoAreaFreeshippingDomQuery leftJoinArea($relationAlias = null) Adds a LEFT JOIN clause to the query using the Area relation
 * @method     ChildSocolissimoAreaFreeshippingDomQuery rightJoinArea($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Area relation
 * @method     ChildSocolissimoAreaFreeshippingDomQuery innerJoinArea($relationAlias = null) Adds a INNER JOIN clause to the query using the Area relation
 *
 * @method     ChildSocolissimoAreaFreeshippingDomQuery leftJoinSocolissimoDeliveryMode($relationAlias = null) Adds a LEFT JOIN clause to the query using the SocolissimoDeliveryMode relation
 * @method     ChildSocolissimoAreaFreeshippingDomQuery rightJoinSocolissimoDeliveryMode($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SocolissimoDeliveryMode relation
 * @method     ChildSocolissimoAreaFreeshippingDomQuery innerJoinSocolissimoDeliveryMode($relationAlias = null) Adds a INNER JOIN clause to the query using the SocolissimoDeliveryMode relation
 *
 * @method     ChildSocolissimoAreaFreeshippingDom findOne(ConnectionInterface $con = null) Return the first ChildSocolissimoAreaFreeshippingDom matching the query
 * @method     ChildSocolissimoAreaFreeshippingDom findOneOrCreate(ConnectionInterface $con = null) Return the first ChildSocolissimoAreaFreeshippingDom matching the query, or a new ChildSocolissimoAreaFreeshippingDom object populated from the query conditions when no match is found
 *
 * @method     ChildSocolissimoAreaFreeshippingDom findOneById(int $id) Return the first ChildSocolissimoAreaFreeshippingDom filtered by the id column
 * @method     ChildSocolissimoAreaFreeshippingDom findOneByAreaId(int $area_id) Return the first ChildSocolissimoAreaFreeshippingDom filtered by the area_id column
 * @method     ChildSocolissimoAreaFreeshippingDom findOneByDeliveryModeId(int $delivery_mode_id) Return the first ChildSocolissimoAreaFreeshippingDom filtered by the delivery_mode_id column
 * @method     ChildSocolissimoAreaFreeshippingDom findOneByCartAmount(string $cart_amount) Return the first ChildSocolissimoAreaFreeshippingDom filtered by the cart_amount column
 *
 * @method     array findById(int $id) Return ChildSocolissimoAreaFreeshippingDom objects filtered by the id column
 * @method     array findByAreaId(int $area_id) Return ChildSocolissimoAreaFreeshippingDom objects filtered by the area_id column
 * @method     array findByDeliveryModeId(int $delivery_mode_id) Return ChildSocolissimoAreaFreeshippingDom objects filtered by the delivery_mode_id column
 * @method     array findByCartAmount(string $cart_amount) Return ChildSocolissimoAreaFreeshippingDom objects filtered by the cart_amount column
 *
 */
abstract class SocolissimoAreaFreeshippingDomQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \SoColissimo\Model\Base\SocolissimoAreaFreeshippingDomQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\SoColissimo\\Model\\SocolissimoAreaFreeshippingDom', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildSocolissimoAreaFreeshippingDomQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildSocolissimoAreaFreeshippingDomQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \SoColissimo\Model\SocolissimoAreaFreeshippingDomQuery) {
            return $criteria;
        }
        $query = new \SoColissimo\Model\SocolissimoAreaFreeshippingDomQuery();
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
     * @return ChildSocolissimoAreaFreeshippingDom|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SocolissimoAreaFreeshippingDomTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SocolissimoAreaFreeshippingDomTableMap::DATABASE_NAME);
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
     * @return   ChildSocolissimoAreaFreeshippingDom A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, AREA_ID, DELIVERY_MODE_ID, CART_AMOUNT FROM socolissimo_area_freeshipping_dom WHERE ID = :p0';
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
            $obj = new ChildSocolissimoAreaFreeshippingDom();
            $obj->hydrate($row);
            SocolissimoAreaFreeshippingDomTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildSocolissimoAreaFreeshippingDom|array|mixed the result, formatted by the current formatter
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
     * @return ChildSocolissimoAreaFreeshippingDomQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SocolissimoAreaFreeshippingDomTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildSocolissimoAreaFreeshippingDomQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SocolissimoAreaFreeshippingDomTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildSocolissimoAreaFreeshippingDomQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SocolissimoAreaFreeshippingDomTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SocolissimoAreaFreeshippingDomTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SocolissimoAreaFreeshippingDomTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the area_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAreaId(1234); // WHERE area_id = 1234
     * $query->filterByAreaId(array(12, 34)); // WHERE area_id IN (12, 34)
     * $query->filterByAreaId(array('min' => 12)); // WHERE area_id > 12
     * </code>
     *
     * @see       filterByArea()
     *
     * @param     mixed $areaId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSocolissimoAreaFreeshippingDomQuery The current query, for fluid interface
     */
    public function filterByAreaId($areaId = null, $comparison = null)
    {
        if (is_array($areaId)) {
            $useMinMax = false;
            if (isset($areaId['min'])) {
                $this->addUsingAlias(SocolissimoAreaFreeshippingDomTableMap::AREA_ID, $areaId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($areaId['max'])) {
                $this->addUsingAlias(SocolissimoAreaFreeshippingDomTableMap::AREA_ID, $areaId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SocolissimoAreaFreeshippingDomTableMap::AREA_ID, $areaId, $comparison);
    }

    /**
     * Filter the query on the delivery_mode_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDeliveryModeId(1234); // WHERE delivery_mode_id = 1234
     * $query->filterByDeliveryModeId(array(12, 34)); // WHERE delivery_mode_id IN (12, 34)
     * $query->filterByDeliveryModeId(array('min' => 12)); // WHERE delivery_mode_id > 12
     * </code>
     *
     * @see       filterBySocolissimoDeliveryMode()
     *
     * @param     mixed $deliveryModeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSocolissimoAreaFreeshippingDomQuery The current query, for fluid interface
     */
    public function filterByDeliveryModeId($deliveryModeId = null, $comparison = null)
    {
        if (is_array($deliveryModeId)) {
            $useMinMax = false;
            if (isset($deliveryModeId['min'])) {
                $this->addUsingAlias(SocolissimoAreaFreeshippingDomTableMap::DELIVERY_MODE_ID, $deliveryModeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deliveryModeId['max'])) {
                $this->addUsingAlias(SocolissimoAreaFreeshippingDomTableMap::DELIVERY_MODE_ID, $deliveryModeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SocolissimoAreaFreeshippingDomTableMap::DELIVERY_MODE_ID, $deliveryModeId, $comparison);
    }

    /**
     * Filter the query on the cart_amount column
     *
     * Example usage:
     * <code>
     * $query->filterByCartAmount(1234); // WHERE cart_amount = 1234
     * $query->filterByCartAmount(array(12, 34)); // WHERE cart_amount IN (12, 34)
     * $query->filterByCartAmount(array('min' => 12)); // WHERE cart_amount > 12
     * </code>
     *
     * @param     mixed $cartAmount The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSocolissimoAreaFreeshippingDomQuery The current query, for fluid interface
     */
    public function filterByCartAmount($cartAmount = null, $comparison = null)
    {
        if (is_array($cartAmount)) {
            $useMinMax = false;
            if (isset($cartAmount['min'])) {
                $this->addUsingAlias(SocolissimoAreaFreeshippingDomTableMap::CART_AMOUNT, $cartAmount['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cartAmount['max'])) {
                $this->addUsingAlias(SocolissimoAreaFreeshippingDomTableMap::CART_AMOUNT, $cartAmount['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SocolissimoAreaFreeshippingDomTableMap::CART_AMOUNT, $cartAmount, $comparison);
    }

    /**
     * Filter the query by a related \SoColissimo\Model\Thelia\Model\Area object
     *
     * @param \SoColissimo\Model\Thelia\Model\Area|ObjectCollection $area The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSocolissimoAreaFreeshippingDomQuery The current query, for fluid interface
     */
    public function filterByArea($area, $comparison = null)
    {
        if ($area instanceof \SoColissimo\Model\Thelia\Model\Area) {
            return $this
                ->addUsingAlias(SocolissimoAreaFreeshippingDomTableMap::AREA_ID, $area->getId(), $comparison);
        } elseif ($area instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SocolissimoAreaFreeshippingDomTableMap::AREA_ID, $area->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByArea() only accepts arguments of type \SoColissimo\Model\Thelia\Model\Area or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Area relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildSocolissimoAreaFreeshippingDomQuery The current query, for fluid interface
     */
    public function joinArea($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Area');

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
            $this->addJoinObject($join, 'Area');
        }

        return $this;
    }

    /**
     * Use the Area relation Area object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \SoColissimo\Model\Thelia\Model\AreaQuery A secondary query class using the current class as primary query
     */
    public function useAreaQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinArea($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Area', '\SoColissimo\Model\Thelia\Model\AreaQuery');
    }

    /**
     * Filter the query by a related \SoColissimo\Model\SocolissimoDeliveryMode object
     *
     * @param \SoColissimo\Model\SocolissimoDeliveryMode|ObjectCollection $socolissimoDeliveryMode The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildSocolissimoAreaFreeshippingDomQuery The current query, for fluid interface
     */
    public function filterBySocolissimoDeliveryMode($socolissimoDeliveryMode, $comparison = null)
    {
        if ($socolissimoDeliveryMode instanceof \SoColissimo\Model\SocolissimoDeliveryMode) {
            return $this
                ->addUsingAlias(SocolissimoAreaFreeshippingDomTableMap::DELIVERY_MODE_ID, $socolissimoDeliveryMode->getId(), $comparison);
        } elseif ($socolissimoDeliveryMode instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SocolissimoAreaFreeshippingDomTableMap::DELIVERY_MODE_ID, $socolissimoDeliveryMode->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySocolissimoDeliveryMode() only accepts arguments of type \SoColissimo\Model\SocolissimoDeliveryMode or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SocolissimoDeliveryMode relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildSocolissimoAreaFreeshippingDomQuery The current query, for fluid interface
     */
    public function joinSocolissimoDeliveryMode($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SocolissimoDeliveryMode');

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
            $this->addJoinObject($join, 'SocolissimoDeliveryMode');
        }

        return $this;
    }

    /**
     * Use the SocolissimoDeliveryMode relation SocolissimoDeliveryMode object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \SoColissimo\Model\SocolissimoDeliveryModeQuery A secondary query class using the current class as primary query
     */
    public function useSocolissimoDeliveryModeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSocolissimoDeliveryMode($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SocolissimoDeliveryMode', '\SoColissimo\Model\SocolissimoDeliveryModeQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildSocolissimoAreaFreeshippingDom $socolissimoAreaFreeshippingDom Object to remove from the list of results
     *
     * @return ChildSocolissimoAreaFreeshippingDomQuery The current query, for fluid interface
     */
    public function prune($socolissimoAreaFreeshippingDom = null)
    {
        if ($socolissimoAreaFreeshippingDom) {
            $this->addUsingAlias(SocolissimoAreaFreeshippingDomTableMap::ID, $socolissimoAreaFreeshippingDom->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the socolissimo_area_freeshipping_dom table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(SocolissimoAreaFreeshippingDomTableMap::DATABASE_NAME);
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
            SocolissimoAreaFreeshippingDomTableMap::clearInstancePool();
            SocolissimoAreaFreeshippingDomTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildSocolissimoAreaFreeshippingDom or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildSocolissimoAreaFreeshippingDom object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(SocolissimoAreaFreeshippingDomTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(SocolissimoAreaFreeshippingDomTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        SocolissimoAreaFreeshippingDomTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            SocolissimoAreaFreeshippingDomTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // SocolissimoAreaFreeshippingDomQuery
