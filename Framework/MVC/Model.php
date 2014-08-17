<?php
/**
 * Base abstract model class
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\MVC;

/**
 * Class Model
 * @package Framework\MVC
 */
abstract class Model
{
    protected $_di;

    public function __construct()
    {
       // $this->_di = $di;
    }

    /**
     * prepare data from database/storage and get it
     */
    public function getData()
	{
	}
}