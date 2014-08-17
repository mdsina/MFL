<?php
/**
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\Session;

interface SpecialSessionInterface extends \SessionHandlerInterface
{
    public function open($sessionId, $savePath = '');
}