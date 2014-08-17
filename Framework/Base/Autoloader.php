<?php
/**
 * Main class auto loader, PSR-0 standard
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\Base;

/**
 * Class Autoloader
 * @package Framework\Base
 */
class Autoloader
{
    private $_fileExtension = '.php';
    private $_namespace;
    private $_includePath;
    private $_namespaceSeparator = '\\';


    /**
     * Constructor
     * Initializing include path and namespace
     *
     * @param string $namespace
     * @param string $includePath
     */
    public function __construct($namespace = null, $includePath = null)
    {
        $this->_namespace = $namespace;
        $this->_includePath = $includePath;
    }


    /**
     * Set namespace separator to use
     *
     * @param string $separator
     */
    public function setNamespaceSeparator($separator)
    {
        $this->_namespaceSeparator = $separator;
    }


    /**
     * get namespace separator
     *
     * @return string
     */
    public function getNamespaceSeparator()
    {
        return $this->_namespaceSeparator;
    }


    /**
     * set base include path for loading classes, namespaces etc.
     *
     * @param string $includePath
     */
    public function setIncludePath($includePath)
    {
        $this->_includePath = $includePath;
    }


    /**
     * get autoloader include path
     *
     * @return string
     */
    public function getIncludePath()
    {
        return $this->_includePath;
    }


    /**
     * set file extension for autoloading likes '.php', '.php5' etc
     *
     * @param string $fileExtension
     */
    public function setFileExtension($fileExtension)
    {
        $this->_fileExtension = $fileExtension;
    }


    /**
     * get file extension
     *
     * @return string
     */
    public function getFileExtension()
    {
        return $this->_fileExtension;
    }


    /**
     * use SPL autoload for registering class loader method
     */
    public function register()
    {
        spl_autoload_register([$this, 'load']);
    }


    /**
     * unregister loading function from SPL
     *
     * may be something useful, for use another function to load from extended class
     */
    public function unRegister()
    {
        spl_autoload_unregister([$this, 'load']);
    }


    /**
     * Loading class by name
     *
     * @param string $className
     * @return bool
     */
    public function load($className)
    {
        $fullNamespace = $this->_namespace . $this->getNamespaceSeparator();

        if (null === $this->_namespace || $fullNamespace === substr($className, 0, strlen($fullNamespace))) {
            $fileName = '';

            if (false !== ($lastNsPos = strripos($className, $this->getNamespaceSeparator()))) {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $fileName = str_replace(
                        $this->_namespaceSeparator,
                        DIRECTORY_SEPARATOR,
                        $namespace
                    ) . DIRECTORY_SEPARATOR;
            }

            $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . $this->getFileExtension();
            $filePath  = stream_resolve_include_path($fileName);

            if ($filePath) {
                require_once($filePath);
            }

            return $filePath !== false;
        }

        return false;
    }
}