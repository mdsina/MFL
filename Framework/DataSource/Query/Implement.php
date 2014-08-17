<?php
/**
 * Query class
 *
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\DataSource\Query;

/**
 * Some abstraction for different interfaces for work with query
 * Implementation itself allows the use of a fluid interface, which can sometimes even easier
 * Like this: $this->select(...)->from(...)->where(...)->either()->where()->groupBy(..);
 *
 * Class Implement
 * @package Framework\DataSource\Query
 */
class Implement implements QueryInterface
{
    /**
     * Where fields, likes ['a >'] = 2, ['b IN(?)'] = [1,2,3,4,5] ...
     *
     * @var array
     */
    protected $_where = [];

    /**
     * OR operators stack, it's need for abstract interface, because not all SQL-like interfaces having this operator
     *
     * @var array
     */
    protected $_orStack = [];

    /**
     * select fields for update/delete/insert/select
     *
     * @var array
     */
    protected $_selectedFields = [];

    /**
     * Limit result count
     *
     * @var int
     */
    protected $_limit;

    /**
     * group results by field
     *
     * @var string
     */
    protected $_groupBy;

    /**
     * sort results by field
     *
     * @var string
     */
    protected $_orderBy;

    /**
     * full query string
     *
     * @var string
     */
    protected $_query;

    /**
     * Collection or table
     *
     * @var string
     */
    protected $_collection;

    /**
     * operation flag
     *
     * ['delete', 'select', 'update', 'insert'] maybe something more
     *
     * @var string
     */
    protected $_flag;

    /**
     * Of cause i can use count(_where), but it faster
     *
     * @var int
     */
    protected $_whereCount = 0;

    /**
     * where in query
     *
     * add values to build it by Query Provider
     *
     * @param string|array $field
     * @param array|string $value
     * @return Implement
     */
    public function where($field, $value = null)
    {
        if (empty($value) && is_array($field)) {
            $this->_where = array_merge($this->_where, $field);
            $this->_whereCount = count($this->_where);

            return $this;
        }

        if (!is_array($value)) {
            $value = array($value);
        }

        $this->_where[$field] = $value;
        $this->_whereCount++;

        return $this;
    }


    /**
     * @return Implement
     */
    public function either()
    {
        $this->_orStack[] = $this->_whereCount;

        return $this;
    }


    /**
     * select fields from db
     *
     * @param array|string $fields
     * @return Implement
     */
    public function select($fields)
    {
        if (!is_array($fields)) {
            $fields = array($fields);
        }

        $this->clear();

        $this->_selectedFields = $fields;
        $this->_flag = 'select';

        return $this;
    }


    /**
     * delete fields from db
     *
     * $fields = ['a', 'b' ...] || $fields = 'a'
     *
     * @param array|string $fields
     * @return Implement
     */
    public function delete($fields)
    {
        if (!is_array($fields)) {
            $fields = array($fields);
        }

        $this->clear();

        $this->_selectedFields = $fields;
        $this->_flag = 'delete';

        return $this;
    }


    /**
     * Update data
     *
     * $fields = ['field1' => 'value'...]
     *
     * @param array $fields
     * @return Implement
     */
    public function update($fields)
    {
        if (!is_array($fields)) {
            $fields = array($fields);
        }

        $this->clear();

        $this->_selectedFields = $fields;
        $this->_flag = 'update';

        return $this;
    }


    /**
     * Insert data
     *
     * $fields = ['field1' => 'value'...]
     *
     * @param array $fields
     * @return Implement
     */
    public function insert(array $fields)
    {
        $this->clear();

        $this->_selectedFields = $fields;
        $this->_flag = 'insert';

        return $this;
    }


    /**
     * Group result by field
     *
     * @param string $fields
     * @return Implement
     */
    public function groupBy($fields)
    {
        if (!is_array($fields)) {
            $fields = array($fields);
        }

        $this->_groupBy = $fields;

        return $this;
    }


    /**
     * Sort result by field
     *
     * @param string $field
     * @return Implement
     */
    public function orderBy($field)
    {
        $this->_orderBy = $field;

        return $this;
    }


    /**
     * Limit query result
     *
     * @param int|string $limitCount
     * @return Implement
     */
    public function limit($limitCount = 100)
    {
        $this->_limit = $limitCount;

        return $this;
    }


    /**
     * Raw query
     *
     * IMPORTANT: security of your raw queries lies with you
     *
     * @param string $query
     * @return Implement
     */
    public function raw($query)
    {
        $this->clear();

        $this->_query = $query;

        return $this;
    }


    /**
     * Select collection or table from database
     *
     * @param string $collection
     * @return Implement
     */
    public function collection($collection)
    {
        if (!is_array($collection)) {
            $collection = array($collection);
        }

        $this->_collection = $collection;

        return $this;
    }


    /**
     * Run query
     */
    public function query()
    {}


    /**
     * Clear state
     *
     * @return Implement
     */
    private function clear()
    {
        $this->_where = [];
        $this->_whereCount = 0;
        $this->_orStack = [];
        $this->_limit = null;
        $this->_flag = null;
        $this->_selectedFields = [];
        $this->_groupBy = null;
        $this->_orderBy = null;
        $this->_collection = [];

        return $this;
    }
}