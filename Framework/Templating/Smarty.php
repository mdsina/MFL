<?php
/**
 * Smarty templating.
 *
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\Templating;

/**
 * Base class for working with smarty. Only API and initialize.
 * Get Smarty object for work, because to support native work with
 * different versions of Smarty really hard and it's a bad idea.
 *
 * Class Smarty
 * @package Framework\Templating
 */
class Smarty implements TemplatingInterface
{
    use Base;

    /**
     * @var Smarty
     */
    private $_smarty;

    public function __construct()
    {
    }


    /**
     * initialize Smarty
     */
    public function Initialize()
    {
        $smartyParams = $this->getParams();
        $this->setSmarty(new \Smarty());

        $this->getSmarty()->template_dir = $smartyParams['template_dir'];
        $this->getSmarty()->compile_dir  = $smartyParams['compile_dir'];
        $this->getSmarty()->config_dir   = $smartyParams['config_dir'];
        $this->getSmarty()->cache_dir    = $smartyParams['cache_dir'];

        $this->getSmarty()->caching = true;

    }


    /**
     * set Smarty caching option
     *
     * @param bool $caching
     */
    public function setCaching($caching)
    {
        $this->getSmarty()->caching = $caching;
    }


    /**
     * get Smarty caching option
     *
     * @return bool
     */
    public function getCaching()
    {
        return $this->getSmarty()->caching;
    }


    /**
     * set Smarty object
     *
     * @param Smarty $smarty
     */
    public function setSmarty(\Smarty $smarty)
    {
        $this->_smarty = $smarty;
    }


    /**
     * get Smarty object
     *
     * @return Smarty
     */
    public function getSmarty()
    {
        return $this->_smarty;
    }


    /**
     * Base render function
     *
     * @param string $template
     * @param array $data
     */
    public function render($template, array $data)
    {
        $this->getSmarty()->assign('data', $data);
        $this->getSmarty()->display($template);
    }
}