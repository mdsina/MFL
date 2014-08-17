<?php
/**
 * Base interface for templating
 *
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\Templating;

/**
 * Interface TemplatingInterface
 * @package Framework\Templating
 */
interface TemplatingInterface
{
    public function render($template, array $data);
    public function Initialize();
}