<?php
/**
 * Base abstract controller class
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\MVC;
use Framework\Di\Di;

/**
 * Class Controller
 * @package Framework\MVC
 */
abstract class Controller
{
    private $_model;
    private $_view;
    private $_di;

    /**
     * Constructor
     *
     * @param Framework_Request $request
     */
    public function __construct(Di $di)
    {
        $this->_di = $di;
    }


    /**
     * set DIC
     *
     * @param Framework_Di $di
     */
    protected function setDi(Di $di)
    {
        $this->_di = $di;
    }


    /**
     * get DIC
     *
     * @return Framework_Di
     */
    protected function getDi()
    {
        return $this->_di;
    }


    /**
     * set View
     *
     * @param View $view
     */
    protected function setView(View $view)
    {
        $this->_view = $view;
    }


    /**
     * get View
     *
     * @return View
     */
    protected function getView()
    {
        return $this->_view;
    }


    /**
     * set Model
     *
     * @param Model $model
     */
    protected function setModel(Model $model)
    {
        $this->_model = $model;
    }


    /**
     * get Model
     *
     * @return Model
     */
    protected function getModel()
    {
        return $this->_model;
    }


    /**
     * Do something before any action
     */
    protected function _beforeAction() {}


    /**
     * Do something after any action
     */
    protected function _afterAction() {}


    /**
     * @param string $name - call function name
     * @param array $args - function arguments
     * @return bool|mixed
     */
    public function __call($name, $args)
    {
        $name = $name . 'Action';
        if (!method_exists($this, $name )) {
            return false;
        }

        $reflection = new \ReflectionMethod($this, $name);

        if (!$reflection->isPublic()) {
            return false;
        }

        $this->_beforeAction();
        call_user_func_array([$this, $name], $args);
        $this->_afterAction();

        return true;
    }


    /**
     * Default action
     *
     * @return $this
     */
    public function viewAction()
    {
        return $this;
    }
}