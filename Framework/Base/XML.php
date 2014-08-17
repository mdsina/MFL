<?php
/**
 * Base working with XML. XML to array, or array to XML.
 *
 * for easy using and compatibility with php array, accept nodes only, attributes will be ignored
 *
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\Base;

/**
 * Class XML
 * @package Framework\Base
 */
class XML
{
    protected $_version = '1.0';
    protected $_encoding = 'UTF-8';
    protected $_root = 'page';


    /**
     * @var XMLWriter
     */
    protected $_writer;


    /**
     * Constructor, initialize writer
     *
     * @param array $types (allow types: write)
     */
    public function __construct($types = [])
    {
        foreach ($types as $type) {
            switch ($type) {
                case 'write' : $this->_writer = new XMLWriter();
                    break;
            }
        }
    }


    /**
     * Convert array to XML
     *
     * @param array $data
     * @param null $default
     * @return null|string
     */
    public function arrayToXml($data = [], $default = null)
    {
        if (!$this->_writer) {
            return $default;
        }

        try {
            $this->_writer->openMemory();
            $this->_writer->startDocument($this->_version, $this->_encoding);
            $this->_writer->startElement($this->_root);

            $this->_a2xml($data);

            $this->_writer->endElement();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return $this->_writer->outputMemory();
    }


    /**
     * Convert XML to array
     *
     * @param string $document
     * @param null $default
     * @return array|mixed|null
     */
    public function xmlToArray($document = null, $default = null)
    {
        $result = [];

        if (!$document) {
            return $default;
        }

        try {
            $xml = simplexml_load_string($document);
            $json = json_encode($xml);
            $result = json_decode($json, true);
            $result = $this->_fixArray($result);
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return $result;
    }


    /**
     * Remove from array 'node_*' and add it as a simple element
     *
     * @param array $array
     * @return array
     */
    protected static function _fixArray(array $array)
    {
        $result = [];

        foreach ($array as $key => $value) {
            $matches = [];
            preg_match('/node_.*/', $key, $matches);

            if (!empty($matches)) {
                if (is_array($value)) {
                    $result[] = self::_fixArray($value);
                } else {
                    $result[] = $value;
                }
            } elseif (is_array($value)) {
                $result[$key] = self::_fixArray($value);
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }


    /**
     * Convert array to XMl
     *
     * @param array $data
     */
    protected function _a2xml(array $data)
    {
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $key = 'node_' . $key;
            }

            if (is_array($value)) {
                $this->_writer->startElement($key);
                $this->_a2xml($value);
                $this->_writer->endElement();
            } else {
                $this->_writer->writeElement($key, $value);
            }
        }
    }


    /**
     * Set xml encoding
     *
     * @param string $encoding
     */
    public function setEncoding($encoding = 'UTF-8')
    {
        $this->_encoding = $encoding;
    }


    /**
     * Set xml version
     *
     * @param string $version
     */
    public function setVersion($version = '1.0')
    {
        $this->_version = $version;
    }


    /**
     * Set root node name
     *
     * @param string $root
     */
    public function setRoot($root = 'page')
    {
        $this->_root = $root;
    }
}