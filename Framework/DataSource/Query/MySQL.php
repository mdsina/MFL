<?php
/**
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\DataSource\Query;

/**
 * Class MySQL
 * @package Framework\DataSource\Query
 */
class MySQL extends Implement
{

    /**
     * @var PDO
     */
    protected $_connection;


    /**
     * Constructor, set connection object
     *
     * @param PDO $connection
     */
    public function __construct($connection)
    {
        $this->_connection = $connection;
    }


    /**
     * proceed query
     *
     * @return array|void
     */
    public function query()
    {
        $sth = $this->_connection->prepare($this->_prepareQuery());
        $sth->execute(array_values($this->_where));

        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }


    /**
     * proceed query by generator
     *
     * @return Generator
     */
    public function getGenerator()
    {
        $sth = $this->_connection->prepare($this->_prepareQuery());
        $sth->execute(array_values($this->_where));

        while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
            yield $row;
        }
    }


    /**
     * escape data for mysql query
     *
     * @param mixed $data
     * @param string $type
     * @return mixed
     */
    private function _escapeData($data, $type)
    {
        if (empty($data)) {
            return [];
        }

        if (is_array($data)) {
            $result = [];

            foreach ($data as $item) {
                $result[] = self::_escapeData($item, $type);
            }

            return $result;
        }

        $data = \Framework\Validate\Validator::escapeData($data, $type);

        //maybe need more abstraction for quote, because not all PDO drivers provide it
        $data = $this->_connection->quote($data);

        return $data;
    }


    /**
     * get prepared tables
     *
     * @return array|mixed|string|bool
     */
    private function _getTables()
    {
        if (empty($this->_collection)) {
            return '';
        }

        $tables = $this->_escapeData($this->_collection, 'str');

        if (is_array($tables)) {
            $tables = implode(',', $tables);
        }

        return $tables;
    }


    /**
     * Prepare select query
     *
     * @return string
     */
    private function _prepareSelect()
    {
        if (!($tables = $this->_getTables())) {
            return '';
        }

        $query = 'SELECT ';

        if (!empty($this->_selectedFields)) {
            $select = $this->_escapeData($this->_selectedFields, 'str');

            $query .= implode(',', $select) . ' ';
        } else {
            $query .= '* ';
        }

        $query .= 'FROM ' . $tables . ' ';

        return $query;
    }


    /**
     * prepare data for Update query
     *
     * @return string
     */
    private function _prepareUpdate()
    {
        if (!($tables = $this->_getTables()) || empty($this->_selectedFields)) {
            return '';
        }

        $query = 'UPDATE ' . $tables . ' SET ';
        $selectKeys = $this->_escapeData(array_keys($this->_selectedFields), 'str');
        $selectValues = $this->_escapeData(array_values($this->_selectedFields), 'str');
        $select = array_combine($selectKeys, $selectValues);
        $select = \Framework\Base\String::assocImplode($select);

        $query .= $select . ' ';

        return $query;
    }


    /**
     * prepare data for insertion
     *
     * @return string
     */
    private function _prepareInsert()
    {
        if (!($tables = $this->_getTables()) || empty ($this->_selectedFields)) {
            return '';
        }

        $query = 'INSERT INTO ' . $tables . ' (';

        $selectKeys = $this->_escapeData(array_keys($this->_selectedFields), 'str');
        $selectValues = $this->_escapeData(array_values($this->_selectedFields), 'str');

        $query .= implode(',', $selectKeys) . ') ';
        $query .= 'VALUES (' . implode(',', $selectValues) . ') ';

        return $query;
    }


    /**
     * prepare data for deletion
     *
     * @return string
     */
    private function _prepareDelete()
    {
        if (!($tables = $this->_getTables())) {
            return '';
        }

        $query = 'DELETE ';
        $select = $this->_escapeData($this->_selectedFields, 'str');
        $query .= implode(',', $select) . ' ';
        $query .= 'FROM ' . $tables . ' ';

        return $query;
    }


    /**
     * prepare Where fields
     *
     * @return string
     */
    private function _prepareWhere()
    {
        if ($this->_whereCount == 0) {
            return '';
        }

        $query = 'WHERE ';
        $index = 1;

        foreach ($this->_where as $field) {
            $query .= $field . ' ';

            if ($index != $this->_whereCount) {
                if (in_array($index, $this->_orStack)) {
                    $query .= 'OR ';
                } else {
                    $query .= 'AND ';
                }
            }

            $index++;
        }

        return $query;
    }


    /**
     * Prepare data to full SQL query
     *
     * @return string
     */
    private function _prepareQuery()
    {
        if (!empty($this->_query)) {
            return $this->_query;
        }

        if (empty($this->_collection)) {
            return '';
        }

        $query = '';

        switch ($this->_flag) {
            case 'select':
                $query .= $this->_prepareSelect();
                break;
            case 'update':
                $query .= $this->_prepareUpdate();
                break;
            case 'insert':
                $query .= $this->_prepareInsert();
                break;
            case 'delete':
                $query .= $this->_prepareDelete();

        }

        $query .= $this->_prepareWhere();

        if (!empty($this->_groupBy)) {
            $query .= 'GROUP BY ' . implode(',', $this->_escapeData($this->_groupBy, 'str')) . ' ';
        }

        if (!empty($this->_orderBy)) {
            $query .= 'ORDER BY ' . $this->_escapeData($this->_orderBy, 'str') . ' ';
        }

        if (!empty($this->_limit)) {
            if (is_array($this->_limit)) {
                $limit = implode(',', $this->_escapeData($this->_limit, 'int'));
            } else {
                $limit = $this->_limit;
            }

            $query .= 'LIMIT ' . $limit;
        }

        return $query;
    }
}