<?php
/**
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */


namespace Framework\Session;

/**
 * Default framework session handler
 *
 * Class FileSessionHandler
 * @package Framework\FileSessionHandler
 */
class FileSessionHandler extends \SessionHandler implements SpecialSessionInterface {

    private $_savePath;

    public function __construct()
    {
        $this->_savePath = session_save_path();
    }

    public function open($sessionId, $savePath = '')
    {
        if (empty($sessionId)) {
            throw new \InvalidArgumentException('session id is empty');
        }

        parent::open(
            !empty($savePath) ? $savePath : $this->_savePath,
            $sessionId
        );
    }

    /**
     * Set path for save sessions
     *
     * @TODO: it's simple check if it's not object and not empty, but need method to checking if it's true path
     *
     * @param string $path
     * @return bool - true for success
     */
    public function setSavePath($path = '')
    {
        if (!empty($path) && !is_object($path)) {
            $this->_savePath = $path;

            if (is_writable(realpath($this->_savePath))) {
                session_save_path($this->_savePath);

                return true;
            }
        }

        return false;
    }


    /**
     * Get path to sessions
     *
     * @return string
     */
    public function getSavePath() {
        return $this->_savePath;
    }



} 