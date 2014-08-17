<?php
/**
 * XSLT templating
 *
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\Templating;

/**
 * Class XSLT
 * @package Framework\Templating
 */
class XSLT implements TemplatingInterface
{
    protected $_xml;
    protected $_xsl;

    use Base;

    /**
     * Constructor, Initialize XML and xsl DOMDocument
     */
    public function __construct()
    {
        $this->_xml = new \Framework\Base\XML(['write']);

        //need info about charset, but f#ck it, I have no time to provide it :\
        $this->_xsl = new \DOMDocument(null, 'UTF-8');

    }


    /**
     * Initialize
     */
    public function Initialize()
    {

    }


    /**
     * XSLT Render method, convert data to xml and provide it by XSLTProcessor
     *
     * @param $template
     * @param array $data
     */
    public function render($template, array $data)
    {
        try {
            if (!$this->_xsl->load($template)) {
                throw new \Framework_Exception_File('Template "' . $template . '" not loaded');
            }
        } catch (\Framework_Exception_File $e) {
            echo $e->getMessage();
        }

        // ugh...
        $templateXmlData = new \DOMDocument('1.0', 'UTF-8');

        // hm, maybe some exception... another time
        $templateXmlData->loadXML($this->_xml->arrayToXml($data));

        $processor = new \XSLTProcessor();
        $processor->importStylesheet($this->_xsl);

        echo $processor->transformToDoc($templateXmlData)->saveHTML();
    }
}