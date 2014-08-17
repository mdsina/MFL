<?php
/**
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\Templating;

/**
 * Class Base
 * @package Framework\Templating
 */
trait Base
{
    private $_params = [];

    public function setParams(array $params = [])
    {
        $this->_params = $params;
    }

    public function getParams()
    {
        return $this->_params;
    }
}