<?php
/**
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\Templating;

/**
 * Class Factory
 * @package Framework\Templating
 */
class Factory
{

    /**
     * @var TemplatingInterface
     */
    private $_templater;


    /**
     * @var string
     */
    private $_type;


    private $_params = [];


    /**
     * @param string $type
     */
    public function __construct($type, $params = [])
    {
        $this->setType($type);
        $this->_params = $params;
    }


    /**
     * Change templater
     *
     * @param TemplatingInterface $templater
     */
    public function setTemplater(TemplatingInterface $templater)
    {
        $this->_templater = $templater;
    }


    /**
     * get templater
     *
     * @return TemplatingInterface
     */
    public function getTemplater()
    {
        return $this->_templater;
    }


    /**
     * Get type of templater
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }


    /**
     * set templater type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->_type = $type;
    }


    /**
     * Initialize templater by type
     *
     * @param string $type
     */
    public function initTemplater($type = null)
    {
        if (empty($type)) {
            $type =  $this->getType();
        }

        // WTF?
        $defaultTemplater = '\\Framework\\Templating\\Native';

        if (class_exists('\\Framework\\Templating\\' . $type)) {
            $defaultTemplater = '\\Framework\\Templating\\' . $type;
        }

        $this->_templater = new $defaultTemplater;
        $this->_templater->setParams($this->_params);
        $this->_templater->Initialize();
    }
}