<?php
/**
 * Native render by php
 *
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\Templating;

/**
 * Class Native
 * @package Framework\Templating
 */
class Native implements TemplatingInterface
{

    use Base;

    /**
     * @param string $template
     * @param array $data
     */
    public function render($template, array $data)
    {
        $renderData = $data;
        include($template);
    }


    public function Initialize()
    {

    }
}