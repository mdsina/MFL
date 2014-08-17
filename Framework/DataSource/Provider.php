<?php
/**
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\DataSource;

/**
 * Class Provider
 * @package Framework\DataSource
 */
class Provider
{
    protected $_connection;

    /**
     * @var QueryInterface
     */
    protected $_query;


    /**
     * Create DataSource provider
     *
     * @param string $queryProvider
     * @param array $params - Connection params, see PDO connection class for details
     * @param string $connectionProvider
     */
    public function __construct($queryProvider, array $params = [], $connectionProvider = 'PDO')
    {
        $defaultQueryProvider = 'MySQL';

        if (class_exists('\\Framework\\DataSource\\Connection\\' . $connectionProvider . 'Connection')) {
            $connectionProvider = '\\Framework\\DataSource\\Connection\\' . $connectionProvider . 'Connection';
        }

        if (class_exists('\\Framework\\DataSource\\Query\\' . $queryProvider)) {
            $defaultQueryProvider = '\\Framework\\DataSource\\Query\\' . $queryProvider;
        }

        $this->_connection = new $connectionProvider($params);
        $this->_query = new $defaultQueryProvider($this->_connection);
    }


    /**
     * Get query provider
     *
     * @return QueryInterface
     */
    public function getQueryProvider()
    {
        return $this->_query;
    }
}