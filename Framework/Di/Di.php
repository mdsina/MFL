<?php
/**
 * Base Dependency Injection class for working with services and API
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\Di;

/**
 * Class Di
 * @package Framework\Di
 */
class Di
{

    /**
     * Services, store anonymous functions
     *
     * @var array
     */
    protected $_services = [];


    /**
     * Instances/result of called service
     *
     * @var array
     */
    protected $_instances = [];


    /**
     * Store service
     *
     * @param string $serviceName
     * @param $function - anonymous function
     */
    public function set($serviceName, $function)
    {
        $this->_services[$serviceName] = $function;
    }


    /**
     * Call service likes factory
     *
     * @param string $serviceName
     * @return mixed - return anonymous function result
     * @throws Framework_Exception_InvalidArgument
     */
    public function getNew($serviceName)
    {
        if (!isset($serviceName, $this->_services)) {
            throw new \Framework_Exception_InvalidArgument(sprintf('Service "%s" is not defined.', $serviceName));
        }

        if (!empty($this->_instances[$serviceName])) {
            return clone $this->_instances[$serviceName];
        }

        return $this->_services[$serviceName]($this);
    }


    /**
     * Call service instance
     *
     * @param $serviceName
     * @return callable
     * @throws Framework_Exception_InvalidArgument
     */
    public function get($serviceName)
    {
        if (!isset($serviceName, $this->_services)) {
            throw new \Framework_Exception_InvalidArgument(sprintf('Service "%s" is not defined.', $serviceName));
        }

        if (!isset($this->_instances[$serviceName])) {
            $this->_instances[$serviceName] = $this->_services[$serviceName]($this);
        }

        return $this->_instances[$serviceName];
    }
}