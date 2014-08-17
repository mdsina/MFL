<?php
/**
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\DataSource\Connection;

/**
 * Interface ConnectionInterface
 * @package Framework\DataSource\Connection
 */
interface ConnectionInterface
{
    public function getConnection();
    public function setConnection($connection = null);
}