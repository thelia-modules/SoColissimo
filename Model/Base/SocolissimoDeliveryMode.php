<?php

namespace SoColissimo\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use SoColissimo\Model\SocolissimoAreaFreeshippingDom as ChildSocolissimoAreaFreeshippingDom;
use SoColissimo\Model\SocolissimoAreaFreeshippingDomQuery as ChildSocolissimoAreaFreeshippingDomQuery;
use SoColissimo\Model\SocolissimoAreaFreeshippingPr as ChildSocolissimoAreaFreeshippingPr;
use SoColissimo\Model\SocolissimoAreaFreeshippingPrQuery as ChildSocolissimoAreaFreeshippingPrQuery;
use SoColissimo\Model\SocolissimoDeliveryMode as ChildSocolissimoDeliveryMode;
use SoColissimo\Model\SocolissimoDeliveryModeQuery as ChildSocolissimoDeliveryModeQuery;
use SoColissimo\Model\SocolissimoPrice as ChildSocolissimoPrice;
use SoColissimo\Model\SocolissimoPriceQuery as ChildSocolissimoPriceQuery;
use SoColissimo\Model\Map\SocolissimoDeliveryModeTableMap;

abstract class SocolissimoDeliveryMode implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\SoColissimo\\Model\\Map\\SocolissimoDeliveryModeTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the code field.
     * @var        string
     */
    protected $code;

    /**
     * The value for the freeshipping_active field.
     * @var        boolean
     */
    protected $freeshipping_active;

    /**
     * The value for the freeshipping_from field.
     * @var        double
     */
    protected $freeshipping_from;

    /**
     * @var        ObjectCollection|ChildSocolissimoPrice[] Collection to store aggregation of ChildSocolissimoPrice objects.
     */
    protected $collSocolissimoPrices;
    protected $collSocolissimoPricesPartial;

    /**
     * @var        ObjectCollection|ChildSocolissimoAreaFreeshippingDom[] Collection to store aggregation of ChildSocolissimoAreaFreeshippingDom objects.
     */
    protected $collSocolissimoAreaFreeshippingDoms;
    protected $collSocolissimoAreaFreeshippingDomsPartial;

    /**
     * @var        ObjectCollection|ChildSocolissimoAreaFreeshippingPr[] Collection to store aggregation of ChildSocolissimoAreaFreeshippingPr objects.
     */
    protected $collSocolissimoAreaFreeshippingPrs;
    protected $collSocolissimoAreaFreeshippingPrsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $socolissimoPricesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $socolissimoAreaFreeshippingDomsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $socolissimoAreaFreeshippingPrsScheduledForDeletion = null;

    /**
     * Initializes internal state of SoColissimo\Model\Base\SocolissimoDeliveryMode object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (Boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (Boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>SocolissimoDeliveryMode</code> instance.  If
     * <code>obj</code> is an instance of <code>SocolissimoDeliveryMode</code>, delegates to
     * <code>equals(SocolissimoDeliveryMode)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        $thisclazz = get_class($this);
        if (!is_object($obj) || !($obj instanceof $thisclazz)) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey()
            || null === $obj->getPrimaryKey())  {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        if (null !== $this->getPrimaryKey()) {
            return crc32(serialize($this->getPrimaryKey()));
        }

        return crc32(serialize(clone $this));
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return SocolissimoDeliveryMode The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     *
     * @return SocolissimoDeliveryMode The current object, for fluid interface
     */
    public function importFrom($parser, $data)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), TableMap::TYPE_PHPNAME);

        return $this;
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [id] column value.
     *
     * @return   int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [title] column value.
     *
     * @return   string
     */
    public function getTitle()
    {

        return $this->title;
    }

    /**
     * Get the [code] column value.
     *
     * @return   string
     */
    public function getCode()
    {

        return $this->code;
    }

    /**
     * Get the [freeshipping_active] column value.
     *
     * @return   boolean
     */
    public function getFreeshippingActive()
    {

        return $this->freeshipping_active;
    }

    /**
     * Get the [freeshipping_from] column value.
     *
     * @return   double
     */
    public function getFreeshippingFrom()
    {

        return $this->freeshipping_from;
    }

    /**
     * Set the value of [id] column.
     *
     * @param      int $v new value
     * @return   \SoColissimo\Model\SocolissimoDeliveryMode The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[SocolissimoDeliveryModeTableMap::ID] = true;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [title] column.
     *
     * @param      string $v new value
     * @return   \SoColissimo\Model\SocolissimoDeliveryMode The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[SocolissimoDeliveryModeTableMap::TITLE] = true;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [code] column.
     *
     * @param      string $v new value
     * @return   \SoColissimo\Model\SocolissimoDeliveryMode The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[SocolissimoDeliveryModeTableMap::CODE] = true;
        }


        return $this;
    } // setCode()

    /**
     * Sets the value of the [freeshipping_active] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param      boolean|integer|string $v The new value
     * @return   \SoColissimo\Model\SocolissimoDeliveryMode The current object (for fluent API support)
     */
    public function setFreeshippingActive($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->freeshipping_active !== $v) {
            $this->freeshipping_active = $v;
            $this->modifiedColumns[SocolissimoDeliveryModeTableMap::FREESHIPPING_ACTIVE] = true;
        }


        return $this;
    } // setFreeshippingActive()

    /**
     * Set the value of [freeshipping_from] column.
     *
     * @param      double $v new value
     * @return   \SoColissimo\Model\SocolissimoDeliveryMode The current object (for fluent API support)
     */
    public function setFreeshippingFrom($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->freeshipping_from !== $v) {
            $this->freeshipping_from = $v;
            $this->modifiedColumns[SocolissimoDeliveryModeTableMap::FREESHIPPING_FROM] = true;
        }


        return $this;
    } // setFreeshippingFrom()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {


            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : SocolissimoDeliveryModeTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : SocolissimoDeliveryModeTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : SocolissimoDeliveryModeTableMap::translateFieldName('Code', TableMap::TYPE_PHPNAME, $indexType)];
            $this->code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : SocolissimoDeliveryModeTableMap::translateFieldName('FreeshippingActive', TableMap::TYPE_PHPNAME, $indexType)];
            $this->freeshipping_active = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : SocolissimoDeliveryModeTableMap::translateFieldName('FreeshippingFrom', TableMap::TYPE_PHPNAME, $indexType)];
            $this->freeshipping_from = (null !== $col) ? (double) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = SocolissimoDeliveryModeTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating \SoColissimo\Model\SocolissimoDeliveryMode object", 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(SocolissimoDeliveryModeTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildSocolissimoDeliveryModeQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collSocolissimoPrices = null;

            $this->collSocolissimoAreaFreeshippingDoms = null;

            $this->collSocolissimoAreaFreeshippingPrs = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see SocolissimoDeliveryMode::setDeleted()
     * @see SocolissimoDeliveryMode::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SocolissimoDeliveryModeTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ChildSocolissimoDeliveryModeQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(SocolissimoDeliveryModeTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                SocolissimoDeliveryModeTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->socolissimoPricesScheduledForDeletion !== null) {
                if (!$this->socolissimoPricesScheduledForDeletion->isEmpty()) {
                    \SoColissimo\Model\SocolissimoPriceQuery::create()
                        ->filterByPrimaryKeys($this->socolissimoPricesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->socolissimoPricesScheduledForDeletion = null;
                }
            }

                if ($this->collSocolissimoPrices !== null) {
            foreach ($this->collSocolissimoPrices as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->socolissimoAreaFreeshippingDomsScheduledForDeletion !== null) {
                if (!$this->socolissimoAreaFreeshippingDomsScheduledForDeletion->isEmpty()) {
                    \SoColissimo\Model\SocolissimoAreaFreeshippingDomQuery::create()
                        ->filterByPrimaryKeys($this->socolissimoAreaFreeshippingDomsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->socolissimoAreaFreeshippingDomsScheduledForDeletion = null;
                }
            }

                if ($this->collSocolissimoAreaFreeshippingDoms !== null) {
            foreach ($this->collSocolissimoAreaFreeshippingDoms as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->socolissimoAreaFreeshippingPrsScheduledForDeletion !== null) {
                if (!$this->socolissimoAreaFreeshippingPrsScheduledForDeletion->isEmpty()) {
                    \SoColissimo\Model\SocolissimoAreaFreeshippingPrQuery::create()
                        ->filterByPrimaryKeys($this->socolissimoAreaFreeshippingPrsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->socolissimoAreaFreeshippingPrsScheduledForDeletion = null;
                }
            }

                if ($this->collSocolissimoAreaFreeshippingPrs !== null) {
            foreach ($this->collSocolissimoAreaFreeshippingPrs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[SocolissimoDeliveryModeTableMap::ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . SocolissimoDeliveryModeTableMap::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(SocolissimoDeliveryModeTableMap::ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }
        if ($this->isColumnModified(SocolissimoDeliveryModeTableMap::TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'TITLE';
        }
        if ($this->isColumnModified(SocolissimoDeliveryModeTableMap::CODE)) {
            $modifiedColumns[':p' . $index++]  = 'CODE';
        }
        if ($this->isColumnModified(SocolissimoDeliveryModeTableMap::FREESHIPPING_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = 'FREESHIPPING_ACTIVE';
        }
        if ($this->isColumnModified(SocolissimoDeliveryModeTableMap::FREESHIPPING_FROM)) {
            $modifiedColumns[':p' . $index++]  = 'FREESHIPPING_FROM';
        }

        $sql = sprintf(
            'INSERT INTO socolissimo_delivery_mode (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'ID':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'TITLE':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case 'CODE':
                        $stmt->bindValue($identifier, $this->code, PDO::PARAM_STR);
                        break;
                    case 'FREESHIPPING_ACTIVE':
                        $stmt->bindValue($identifier, (int) $this->freeshipping_active, PDO::PARAM_INT);
                        break;
                    case 'FREESHIPPING_FROM':
                        $stmt->bindValue($identifier, $this->freeshipping_from, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = SocolissimoDeliveryModeTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getTitle();
                break;
            case 2:
                return $this->getCode();
                break;
            case 3:
                return $this->getFreeshippingActive();
                break;
            case 4:
                return $this->getFreeshippingFrom();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['SocolissimoDeliveryMode'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['SocolissimoDeliveryMode'][$this->getPrimaryKey()] = true;
        $keys = SocolissimoDeliveryModeTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTitle(),
            $keys[2] => $this->getCode(),
            $keys[3] => $this->getFreeshippingActive(),
            $keys[4] => $this->getFreeshippingFrom(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collSocolissimoPrices) {
                $result['SocolissimoPrices'] = $this->collSocolissimoPrices->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSocolissimoAreaFreeshippingDoms) {
                $result['SocolissimoAreaFreeshippingDoms'] = $this->collSocolissimoAreaFreeshippingDoms->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collSocolissimoAreaFreeshippingPrs) {
                $result['SocolissimoAreaFreeshippingPrs'] = $this->collSocolissimoAreaFreeshippingPrs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param      string $name
     * @param      mixed  $value field value
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return void
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = SocolissimoDeliveryModeTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @param      mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setTitle($value);
                break;
            case 2:
                $this->setCode($value);
                break;
            case 3:
                $this->setFreeshippingActive($value);
                break;
            case 4:
                $this->setFreeshippingFrom($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = SocolissimoDeliveryModeTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setTitle($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setCode($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setFreeshippingActive($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setFreeshippingFrom($arr[$keys[4]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(SocolissimoDeliveryModeTableMap::DATABASE_NAME);

        if ($this->isColumnModified(SocolissimoDeliveryModeTableMap::ID)) $criteria->add(SocolissimoDeliveryModeTableMap::ID, $this->id);
        if ($this->isColumnModified(SocolissimoDeliveryModeTableMap::TITLE)) $criteria->add(SocolissimoDeliveryModeTableMap::TITLE, $this->title);
        if ($this->isColumnModified(SocolissimoDeliveryModeTableMap::CODE)) $criteria->add(SocolissimoDeliveryModeTableMap::CODE, $this->code);
        if ($this->isColumnModified(SocolissimoDeliveryModeTableMap::FREESHIPPING_ACTIVE)) $criteria->add(SocolissimoDeliveryModeTableMap::FREESHIPPING_ACTIVE, $this->freeshipping_active);
        if ($this->isColumnModified(SocolissimoDeliveryModeTableMap::FREESHIPPING_FROM)) $criteria->add(SocolissimoDeliveryModeTableMap::FREESHIPPING_FROM, $this->freeshipping_from);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(SocolissimoDeliveryModeTableMap::DATABASE_NAME);
        $criteria->add(SocolissimoDeliveryModeTableMap::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return   int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \SoColissimo\Model\SocolissimoDeliveryMode (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTitle($this->getTitle());
        $copyObj->setCode($this->getCode());
        $copyObj->setFreeshippingActive($this->getFreeshippingActive());
        $copyObj->setFreeshippingFrom($this->getFreeshippingFrom());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getSocolissimoPrices() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSocolissimoPrice($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSocolissimoAreaFreeshippingDoms() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSocolissimoAreaFreeshippingDom($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getSocolissimoAreaFreeshippingPrs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addSocolissimoAreaFreeshippingPr($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return                 \SoColissimo\Model\SocolissimoDeliveryMode Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('SocolissimoPrice' == $relationName) {
            return $this->initSocolissimoPrices();
        }
        if ('SocolissimoAreaFreeshippingDom' == $relationName) {
            return $this->initSocolissimoAreaFreeshippingDoms();
        }
        if ('SocolissimoAreaFreeshippingPr' == $relationName) {
            return $this->initSocolissimoAreaFreeshippingPrs();
        }
    }

    /**
     * Clears out the collSocolissimoPrices collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSocolissimoPrices()
     */
    public function clearSocolissimoPrices()
    {
        $this->collSocolissimoPrices = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSocolissimoPrices collection loaded partially.
     */
    public function resetPartialSocolissimoPrices($v = true)
    {
        $this->collSocolissimoPricesPartial = $v;
    }

    /**
     * Initializes the collSocolissimoPrices collection.
     *
     * By default this just sets the collSocolissimoPrices collection to an empty array (like clearcollSocolissimoPrices());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSocolissimoPrices($overrideExisting = true)
    {
        if (null !== $this->collSocolissimoPrices && !$overrideExisting) {
            return;
        }
        $this->collSocolissimoPrices = new ObjectCollection();
        $this->collSocolissimoPrices->setModel('\SoColissimo\Model\SocolissimoPrice');
    }

    /**
     * Gets an array of ChildSocolissimoPrice objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSocolissimoDeliveryMode is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildSocolissimoPrice[] List of ChildSocolissimoPrice objects
     * @throws PropelException
     */
    public function getSocolissimoPrices($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSocolissimoPricesPartial && !$this->isNew();
        if (null === $this->collSocolissimoPrices || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSocolissimoPrices) {
                // return empty collection
                $this->initSocolissimoPrices();
            } else {
                $collSocolissimoPrices = ChildSocolissimoPriceQuery::create(null, $criteria)
                    ->filterBySocolissimoDeliveryMode($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSocolissimoPricesPartial && count($collSocolissimoPrices)) {
                        $this->initSocolissimoPrices(false);

                        foreach ($collSocolissimoPrices as $obj) {
                            if (false == $this->collSocolissimoPrices->contains($obj)) {
                                $this->collSocolissimoPrices->append($obj);
                            }
                        }

                        $this->collSocolissimoPricesPartial = true;
                    }

                    reset($collSocolissimoPrices);

                    return $collSocolissimoPrices;
                }

                if ($partial && $this->collSocolissimoPrices) {
                    foreach ($this->collSocolissimoPrices as $obj) {
                        if ($obj->isNew()) {
                            $collSocolissimoPrices[] = $obj;
                        }
                    }
                }

                $this->collSocolissimoPrices = $collSocolissimoPrices;
                $this->collSocolissimoPricesPartial = false;
            }
        }

        return $this->collSocolissimoPrices;
    }

    /**
     * Sets a collection of SocolissimoPrice objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $socolissimoPrices A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildSocolissimoDeliveryMode The current object (for fluent API support)
     */
    public function setSocolissimoPrices(Collection $socolissimoPrices, ConnectionInterface $con = null)
    {
        $socolissimoPricesToDelete = $this->getSocolissimoPrices(new Criteria(), $con)->diff($socolissimoPrices);


        $this->socolissimoPricesScheduledForDeletion = $socolissimoPricesToDelete;

        foreach ($socolissimoPricesToDelete as $socolissimoPriceRemoved) {
            $socolissimoPriceRemoved->setSocolissimoDeliveryMode(null);
        }

        $this->collSocolissimoPrices = null;
        foreach ($socolissimoPrices as $socolissimoPrice) {
            $this->addSocolissimoPrice($socolissimoPrice);
        }

        $this->collSocolissimoPrices = $socolissimoPrices;
        $this->collSocolissimoPricesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SocolissimoPrice objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related SocolissimoPrice objects.
     * @throws PropelException
     */
    public function countSocolissimoPrices(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSocolissimoPricesPartial && !$this->isNew();
        if (null === $this->collSocolissimoPrices || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSocolissimoPrices) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSocolissimoPrices());
            }

            $query = ChildSocolissimoPriceQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySocolissimoDeliveryMode($this)
                ->count($con);
        }

        return count($this->collSocolissimoPrices);
    }

    /**
     * Method called to associate a ChildSocolissimoPrice object to this object
     * through the ChildSocolissimoPrice foreign key attribute.
     *
     * @param    ChildSocolissimoPrice $l ChildSocolissimoPrice
     * @return   \SoColissimo\Model\SocolissimoDeliveryMode The current object (for fluent API support)
     */
    public function addSocolissimoPrice(ChildSocolissimoPrice $l)
    {
        if ($this->collSocolissimoPrices === null) {
            $this->initSocolissimoPrices();
            $this->collSocolissimoPricesPartial = true;
        }

        if (!in_array($l, $this->collSocolissimoPrices->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSocolissimoPrice($l);
        }

        return $this;
    }

    /**
     * @param SocolissimoPrice $socolissimoPrice The socolissimoPrice object to add.
     */
    protected function doAddSocolissimoPrice($socolissimoPrice)
    {
        $this->collSocolissimoPrices[]= $socolissimoPrice;
        $socolissimoPrice->setSocolissimoDeliveryMode($this);
    }

    /**
     * @param  SocolissimoPrice $socolissimoPrice The socolissimoPrice object to remove.
     * @return ChildSocolissimoDeliveryMode The current object (for fluent API support)
     */
    public function removeSocolissimoPrice($socolissimoPrice)
    {
        if ($this->getSocolissimoPrices()->contains($socolissimoPrice)) {
            $this->collSocolissimoPrices->remove($this->collSocolissimoPrices->search($socolissimoPrice));
            if (null === $this->socolissimoPricesScheduledForDeletion) {
                $this->socolissimoPricesScheduledForDeletion = clone $this->collSocolissimoPrices;
                $this->socolissimoPricesScheduledForDeletion->clear();
            }
            $this->socolissimoPricesScheduledForDeletion[]= clone $socolissimoPrice;
            $socolissimoPrice->setSocolissimoDeliveryMode(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this SocolissimoDeliveryMode is new, it will return
     * an empty collection; or if this SocolissimoDeliveryMode has previously
     * been saved, it will retrieve related SocolissimoPrices from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SocolissimoDeliveryMode.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildSocolissimoPrice[] List of ChildSocolissimoPrice objects
     */
    public function getSocolissimoPricesJoinArea($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSocolissimoPriceQuery::create(null, $criteria);
        $query->joinWith('Area', $joinBehavior);

        return $this->getSocolissimoPrices($query, $con);
    }

    /**
     * Clears out the collSocolissimoAreaFreeshippingDoms collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSocolissimoAreaFreeshippingDoms()
     */
    public function clearSocolissimoAreaFreeshippingDoms()
    {
        $this->collSocolissimoAreaFreeshippingDoms = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSocolissimoAreaFreeshippingDoms collection loaded partially.
     */
    public function resetPartialSocolissimoAreaFreeshippingDoms($v = true)
    {
        $this->collSocolissimoAreaFreeshippingDomsPartial = $v;
    }

    /**
     * Initializes the collSocolissimoAreaFreeshippingDoms collection.
     *
     * By default this just sets the collSocolissimoAreaFreeshippingDoms collection to an empty array (like clearcollSocolissimoAreaFreeshippingDoms());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSocolissimoAreaFreeshippingDoms($overrideExisting = true)
    {
        if (null !== $this->collSocolissimoAreaFreeshippingDoms && !$overrideExisting) {
            return;
        }
        $this->collSocolissimoAreaFreeshippingDoms = new ObjectCollection();
        $this->collSocolissimoAreaFreeshippingDoms->setModel('\SoColissimo\Model\SocolissimoAreaFreeshippingDom');
    }

    /**
     * Gets an array of ChildSocolissimoAreaFreeshippingDom objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSocolissimoDeliveryMode is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildSocolissimoAreaFreeshippingDom[] List of ChildSocolissimoAreaFreeshippingDom objects
     * @throws PropelException
     */
    public function getSocolissimoAreaFreeshippingDoms($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSocolissimoAreaFreeshippingDomsPartial && !$this->isNew();
        if (null === $this->collSocolissimoAreaFreeshippingDoms || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSocolissimoAreaFreeshippingDoms) {
                // return empty collection
                $this->initSocolissimoAreaFreeshippingDoms();
            } else {
                $collSocolissimoAreaFreeshippingDoms = ChildSocolissimoAreaFreeshippingDomQuery::create(null, $criteria)
                    ->filterBySocolissimoDeliveryMode($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSocolissimoAreaFreeshippingDomsPartial && count($collSocolissimoAreaFreeshippingDoms)) {
                        $this->initSocolissimoAreaFreeshippingDoms(false);

                        foreach ($collSocolissimoAreaFreeshippingDoms as $obj) {
                            if (false == $this->collSocolissimoAreaFreeshippingDoms->contains($obj)) {
                                $this->collSocolissimoAreaFreeshippingDoms->append($obj);
                            }
                        }

                        $this->collSocolissimoAreaFreeshippingDomsPartial = true;
                    }

                    reset($collSocolissimoAreaFreeshippingDoms);

                    return $collSocolissimoAreaFreeshippingDoms;
                }

                if ($partial && $this->collSocolissimoAreaFreeshippingDoms) {
                    foreach ($this->collSocolissimoAreaFreeshippingDoms as $obj) {
                        if ($obj->isNew()) {
                            $collSocolissimoAreaFreeshippingDoms[] = $obj;
                        }
                    }
                }

                $this->collSocolissimoAreaFreeshippingDoms = $collSocolissimoAreaFreeshippingDoms;
                $this->collSocolissimoAreaFreeshippingDomsPartial = false;
            }
        }

        return $this->collSocolissimoAreaFreeshippingDoms;
    }

    /**
     * Sets a collection of SocolissimoAreaFreeshippingDom objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $socolissimoAreaFreeshippingDoms A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildSocolissimoDeliveryMode The current object (for fluent API support)
     */
    public function setSocolissimoAreaFreeshippingDoms(Collection $socolissimoAreaFreeshippingDoms, ConnectionInterface $con = null)
    {
        $socolissimoAreaFreeshippingDomsToDelete = $this->getSocolissimoAreaFreeshippingDoms(new Criteria(), $con)->diff($socolissimoAreaFreeshippingDoms);


        $this->socolissimoAreaFreeshippingDomsScheduledForDeletion = $socolissimoAreaFreeshippingDomsToDelete;

        foreach ($socolissimoAreaFreeshippingDomsToDelete as $socolissimoAreaFreeshippingDomRemoved) {
            $socolissimoAreaFreeshippingDomRemoved->setSocolissimoDeliveryMode(null);
        }

        $this->collSocolissimoAreaFreeshippingDoms = null;
        foreach ($socolissimoAreaFreeshippingDoms as $socolissimoAreaFreeshippingDom) {
            $this->addSocolissimoAreaFreeshippingDom($socolissimoAreaFreeshippingDom);
        }

        $this->collSocolissimoAreaFreeshippingDoms = $socolissimoAreaFreeshippingDoms;
        $this->collSocolissimoAreaFreeshippingDomsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SocolissimoAreaFreeshippingDom objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related SocolissimoAreaFreeshippingDom objects.
     * @throws PropelException
     */
    public function countSocolissimoAreaFreeshippingDoms(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSocolissimoAreaFreeshippingDomsPartial && !$this->isNew();
        if (null === $this->collSocolissimoAreaFreeshippingDoms || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSocolissimoAreaFreeshippingDoms) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSocolissimoAreaFreeshippingDoms());
            }

            $query = ChildSocolissimoAreaFreeshippingDomQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySocolissimoDeliveryMode($this)
                ->count($con);
        }

        return count($this->collSocolissimoAreaFreeshippingDoms);
    }

    /**
     * Method called to associate a ChildSocolissimoAreaFreeshippingDom object to this object
     * through the ChildSocolissimoAreaFreeshippingDom foreign key attribute.
     *
     * @param    ChildSocolissimoAreaFreeshippingDom $l ChildSocolissimoAreaFreeshippingDom
     * @return   \SoColissimo\Model\SocolissimoDeliveryMode The current object (for fluent API support)
     */
    public function addSocolissimoAreaFreeshippingDom(ChildSocolissimoAreaFreeshippingDom $l)
    {
        if ($this->collSocolissimoAreaFreeshippingDoms === null) {
            $this->initSocolissimoAreaFreeshippingDoms();
            $this->collSocolissimoAreaFreeshippingDomsPartial = true;
        }

        if (!in_array($l, $this->collSocolissimoAreaFreeshippingDoms->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSocolissimoAreaFreeshippingDom($l);
        }

        return $this;
    }

    /**
     * @param SocolissimoAreaFreeshippingDom $socolissimoAreaFreeshippingDom The socolissimoAreaFreeshippingDom object to add.
     */
    protected function doAddSocolissimoAreaFreeshippingDom($socolissimoAreaFreeshippingDom)
    {
        $this->collSocolissimoAreaFreeshippingDoms[]= $socolissimoAreaFreeshippingDom;
        $socolissimoAreaFreeshippingDom->setSocolissimoDeliveryMode($this);
    }

    /**
     * @param  SocolissimoAreaFreeshippingDom $socolissimoAreaFreeshippingDom The socolissimoAreaFreeshippingDom object to remove.
     * @return ChildSocolissimoDeliveryMode The current object (for fluent API support)
     */
    public function removeSocolissimoAreaFreeshippingDom($socolissimoAreaFreeshippingDom)
    {
        if ($this->getSocolissimoAreaFreeshippingDoms()->contains($socolissimoAreaFreeshippingDom)) {
            $this->collSocolissimoAreaFreeshippingDoms->remove($this->collSocolissimoAreaFreeshippingDoms->search($socolissimoAreaFreeshippingDom));
            if (null === $this->socolissimoAreaFreeshippingDomsScheduledForDeletion) {
                $this->socolissimoAreaFreeshippingDomsScheduledForDeletion = clone $this->collSocolissimoAreaFreeshippingDoms;
                $this->socolissimoAreaFreeshippingDomsScheduledForDeletion->clear();
            }
            $this->socolissimoAreaFreeshippingDomsScheduledForDeletion[]= clone $socolissimoAreaFreeshippingDom;
            $socolissimoAreaFreeshippingDom->setSocolissimoDeliveryMode(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this SocolissimoDeliveryMode is new, it will return
     * an empty collection; or if this SocolissimoDeliveryMode has previously
     * been saved, it will retrieve related SocolissimoAreaFreeshippingDoms from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SocolissimoDeliveryMode.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildSocolissimoAreaFreeshippingDom[] List of ChildSocolissimoAreaFreeshippingDom objects
     */
    public function getSocolissimoAreaFreeshippingDomsJoinArea($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSocolissimoAreaFreeshippingDomQuery::create(null, $criteria);
        $query->joinWith('Area', $joinBehavior);

        return $this->getSocolissimoAreaFreeshippingDoms($query, $con);
    }

    /**
     * Clears out the collSocolissimoAreaFreeshippingPrs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addSocolissimoAreaFreeshippingPrs()
     */
    public function clearSocolissimoAreaFreeshippingPrs()
    {
        $this->collSocolissimoAreaFreeshippingPrs = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collSocolissimoAreaFreeshippingPrs collection loaded partially.
     */
    public function resetPartialSocolissimoAreaFreeshippingPrs($v = true)
    {
        $this->collSocolissimoAreaFreeshippingPrsPartial = $v;
    }

    /**
     * Initializes the collSocolissimoAreaFreeshippingPrs collection.
     *
     * By default this just sets the collSocolissimoAreaFreeshippingPrs collection to an empty array (like clearcollSocolissimoAreaFreeshippingPrs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initSocolissimoAreaFreeshippingPrs($overrideExisting = true)
    {
        if (null !== $this->collSocolissimoAreaFreeshippingPrs && !$overrideExisting) {
            return;
        }
        $this->collSocolissimoAreaFreeshippingPrs = new ObjectCollection();
        $this->collSocolissimoAreaFreeshippingPrs->setModel('\SoColissimo\Model\SocolissimoAreaFreeshippingPr');
    }

    /**
     * Gets an array of ChildSocolissimoAreaFreeshippingPr objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildSocolissimoDeliveryMode is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildSocolissimoAreaFreeshippingPr[] List of ChildSocolissimoAreaFreeshippingPr objects
     * @throws PropelException
     */
    public function getSocolissimoAreaFreeshippingPrs($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collSocolissimoAreaFreeshippingPrsPartial && !$this->isNew();
        if (null === $this->collSocolissimoAreaFreeshippingPrs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collSocolissimoAreaFreeshippingPrs) {
                // return empty collection
                $this->initSocolissimoAreaFreeshippingPrs();
            } else {
                $collSocolissimoAreaFreeshippingPrs = ChildSocolissimoAreaFreeshippingPrQuery::create(null, $criteria)
                    ->filterBySocolissimoDeliveryMode($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collSocolissimoAreaFreeshippingPrsPartial && count($collSocolissimoAreaFreeshippingPrs)) {
                        $this->initSocolissimoAreaFreeshippingPrs(false);

                        foreach ($collSocolissimoAreaFreeshippingPrs as $obj) {
                            if (false == $this->collSocolissimoAreaFreeshippingPrs->contains($obj)) {
                                $this->collSocolissimoAreaFreeshippingPrs->append($obj);
                            }
                        }

                        $this->collSocolissimoAreaFreeshippingPrsPartial = true;
                    }

                    reset($collSocolissimoAreaFreeshippingPrs);

                    return $collSocolissimoAreaFreeshippingPrs;
                }

                if ($partial && $this->collSocolissimoAreaFreeshippingPrs) {
                    foreach ($this->collSocolissimoAreaFreeshippingPrs as $obj) {
                        if ($obj->isNew()) {
                            $collSocolissimoAreaFreeshippingPrs[] = $obj;
                        }
                    }
                }

                $this->collSocolissimoAreaFreeshippingPrs = $collSocolissimoAreaFreeshippingPrs;
                $this->collSocolissimoAreaFreeshippingPrsPartial = false;
            }
        }

        return $this->collSocolissimoAreaFreeshippingPrs;
    }

    /**
     * Sets a collection of SocolissimoAreaFreeshippingPr objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $socolissimoAreaFreeshippingPrs A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildSocolissimoDeliveryMode The current object (for fluent API support)
     */
    public function setSocolissimoAreaFreeshippingPrs(Collection $socolissimoAreaFreeshippingPrs, ConnectionInterface $con = null)
    {
        $socolissimoAreaFreeshippingPrsToDelete = $this->getSocolissimoAreaFreeshippingPrs(new Criteria(), $con)->diff($socolissimoAreaFreeshippingPrs);


        $this->socolissimoAreaFreeshippingPrsScheduledForDeletion = $socolissimoAreaFreeshippingPrsToDelete;

        foreach ($socolissimoAreaFreeshippingPrsToDelete as $socolissimoAreaFreeshippingPrRemoved) {
            $socolissimoAreaFreeshippingPrRemoved->setSocolissimoDeliveryMode(null);
        }

        $this->collSocolissimoAreaFreeshippingPrs = null;
        foreach ($socolissimoAreaFreeshippingPrs as $socolissimoAreaFreeshippingPr) {
            $this->addSocolissimoAreaFreeshippingPr($socolissimoAreaFreeshippingPr);
        }

        $this->collSocolissimoAreaFreeshippingPrs = $socolissimoAreaFreeshippingPrs;
        $this->collSocolissimoAreaFreeshippingPrsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related SocolissimoAreaFreeshippingPr objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related SocolissimoAreaFreeshippingPr objects.
     * @throws PropelException
     */
    public function countSocolissimoAreaFreeshippingPrs(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collSocolissimoAreaFreeshippingPrsPartial && !$this->isNew();
        if (null === $this->collSocolissimoAreaFreeshippingPrs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collSocolissimoAreaFreeshippingPrs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getSocolissimoAreaFreeshippingPrs());
            }

            $query = ChildSocolissimoAreaFreeshippingPrQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySocolissimoDeliveryMode($this)
                ->count($con);
        }

        return count($this->collSocolissimoAreaFreeshippingPrs);
    }

    /**
     * Method called to associate a ChildSocolissimoAreaFreeshippingPr object to this object
     * through the ChildSocolissimoAreaFreeshippingPr foreign key attribute.
     *
     * @param    ChildSocolissimoAreaFreeshippingPr $l ChildSocolissimoAreaFreeshippingPr
     * @return   \SoColissimo\Model\SocolissimoDeliveryMode The current object (for fluent API support)
     */
    public function addSocolissimoAreaFreeshippingPr(ChildSocolissimoAreaFreeshippingPr $l)
    {
        if ($this->collSocolissimoAreaFreeshippingPrs === null) {
            $this->initSocolissimoAreaFreeshippingPrs();
            $this->collSocolissimoAreaFreeshippingPrsPartial = true;
        }

        if (!in_array($l, $this->collSocolissimoAreaFreeshippingPrs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddSocolissimoAreaFreeshippingPr($l);
        }

        return $this;
    }

    /**
     * @param SocolissimoAreaFreeshippingPr $socolissimoAreaFreeshippingPr The socolissimoAreaFreeshippingPr object to add.
     */
    protected function doAddSocolissimoAreaFreeshippingPr($socolissimoAreaFreeshippingPr)
    {
        $this->collSocolissimoAreaFreeshippingPrs[]= $socolissimoAreaFreeshippingPr;
        $socolissimoAreaFreeshippingPr->setSocolissimoDeliveryMode($this);
    }

    /**
     * @param  SocolissimoAreaFreeshippingPr $socolissimoAreaFreeshippingPr The socolissimoAreaFreeshippingPr object to remove.
     * @return ChildSocolissimoDeliveryMode The current object (for fluent API support)
     */
    public function removeSocolissimoAreaFreeshippingPr($socolissimoAreaFreeshippingPr)
    {
        if ($this->getSocolissimoAreaFreeshippingPrs()->contains($socolissimoAreaFreeshippingPr)) {
            $this->collSocolissimoAreaFreeshippingPrs->remove($this->collSocolissimoAreaFreeshippingPrs->search($socolissimoAreaFreeshippingPr));
            if (null === $this->socolissimoAreaFreeshippingPrsScheduledForDeletion) {
                $this->socolissimoAreaFreeshippingPrsScheduledForDeletion = clone $this->collSocolissimoAreaFreeshippingPrs;
                $this->socolissimoAreaFreeshippingPrsScheduledForDeletion->clear();
            }
            $this->socolissimoAreaFreeshippingPrsScheduledForDeletion[]= clone $socolissimoAreaFreeshippingPr;
            $socolissimoAreaFreeshippingPr->setSocolissimoDeliveryMode(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this SocolissimoDeliveryMode is new, it will return
     * an empty collection; or if this SocolissimoDeliveryMode has previously
     * been saved, it will retrieve related SocolissimoAreaFreeshippingPrs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in SocolissimoDeliveryMode.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildSocolissimoAreaFreeshippingPr[] List of ChildSocolissimoAreaFreeshippingPr objects
     */
    public function getSocolissimoAreaFreeshippingPrsJoinArea($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildSocolissimoAreaFreeshippingPrQuery::create(null, $criteria);
        $query->joinWith('Area', $joinBehavior);

        return $this->getSocolissimoAreaFreeshippingPrs($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->title = null;
        $this->code = null;
        $this->freeshipping_active = null;
        $this->freeshipping_from = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collSocolissimoPrices) {
                foreach ($this->collSocolissimoPrices as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSocolissimoAreaFreeshippingDoms) {
                foreach ($this->collSocolissimoAreaFreeshippingDoms as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collSocolissimoAreaFreeshippingPrs) {
                foreach ($this->collSocolissimoAreaFreeshippingPrs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collSocolissimoPrices = null;
        $this->collSocolissimoAreaFreeshippingDoms = null;
        $this->collSocolissimoAreaFreeshippingPrs = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(SocolissimoDeliveryModeTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
