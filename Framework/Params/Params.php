<?php
/**
 * Working with project params
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\Params;

/**
 * Class Params
 * @package Framework\Params
 */
class Params {

    /**
     * @var array
     */
    protected $_params;


    /**
     * Constructor, loading params from file
     *
     * @param $path - path to file with parameters
     */
    public function __construct($path)
    {

        //try to load default file with parameters, but I think it should be better
        //TO-DO: find solution to resolve it better
        if (!file_exists($path)) {
            if (!file_exists('params.php')) {
                return false;
            }

            require_once('Params.php');

            if (is_null($params)) {
                return false;
            }

            $this->_params = $params;

            return true;
        }

        require_once($path);

        // not so good, duplicated code to load params from default and our file, but I don't want use anymore function
        // in this file for check existing params in file
        if (is_null($params)) {
            return false;
        }

        $this->_params = $params;

        return true;
    }


    /**
     * Set our parameters
     *
     * @param array $params
     */
    public function setParams(array $params = [])
    {
        $this->_params = $params;
    }


    /**
     * Get parameters
     *
     * @param array $filedList
     * @param null $default
     * @return array|null
     */
    public function getParams($filedList, $default = null)
    {
        $filedList = array($filedList);

        if (!$this->_params) {
            return $default;
        }

        if (empty($filedList)) {
            return $this->_params;
        }

        $result = [];

        foreach ($filedList as $fieldName) {
            $result[$fieldName] = !empty($this->_params[$fieldName]) ? $this->_params[$fieldName] : null;
        }

        if (count($result) == 1) {
            // use PHP!! this index is safe
            $result = array_values($result)[0];
        }

        return $result;
    }
} 