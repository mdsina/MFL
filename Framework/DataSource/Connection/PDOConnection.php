<?php
/**
 * Work with MySQL
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\DataSource\Connection;

/**
 * Class PDOConnection
 * @package Framework\DataSource\Connection
 */
class PDOConnection implements ConnectionInterface
{
    /**
     * Pointer to connection
     *
     * @var PDO
     */
    protected $_connection;

    protected $_login;
    protected $_password;
    protected $_dbName;
    protected $_host;
    protected $_port;
    protected $_provider;


    /**
     * Initialize
     *
     * params = login, password, provider( = mysql, mysqli ...), db, host, port
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->_login = !empty($params['login']) ? $params['login'] : null;
        $this->_provider = !empty($params['provider']) ? $params['provider'] : null;
        $this->_password = !empty($params['password']) ? $params['password'] : null;
        $this->_dbName = !empty($params['db']) ? $params['db'] : null;
        $this->_host = !empty($params['host']) ? $params['host'] : null;
        $this->_port = !empty($params['port']) ? $params['port'] : null;

        try {
            $this->setConnection(
                new PDO(
                    sprintf(
                        '%s:host=%s;port=%s;dbname=%s',
                        $this->_provider,
                        $this->_host,
                        $this->_port,
                        $this->_dbName
                    ),
                    $this->_login,
                    $this->_password
                )
            );
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }


    /**
     * Set our preinitialized connection
     *
     * @param PDO $connection
     * @return Framework_DataSource_Connection_SQL
     */
    public function setConnection($connection = null)
    {
        $this->_connection = $connection;

        return $this;
    }


    /**
     * Get connection
     *
     * @param null $default
     * @return null|PDO
     */
    public function getConnection($default = null)
    {
        if (!$this->_connection) {
            return $default;
        }

        return $this->_connection;
    }
}