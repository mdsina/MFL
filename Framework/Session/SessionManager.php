<?php
/**
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\Session;

class SessionManager
{
    private $_sessionHandler;
    private $_hashFunction = null;


    /**
     * @param array $params
     */
    protected function __construct(array $params = [])
    {
        if (empty($params)) {
            $this->_sessionHandler = new FileSessionHandler();

            $this->_hashFunction = function () {
                return sha1(md5(
                    \Framework\Request\User::getUserAgent()
                    . \Framework\Request\User::getIp()
                    . \Framework\Request\Request::getRequestTime()
                ));
            };

            return $this;
        }
    }


    /**
     * Open session by SID or create new
     *
     * @param string $sessionId
     * @return mixed
     */
    public function open($sessionId = null)
    {
        if (is_null($sessionId)) {
            $sessionId = $this->_getHash();
        }

        return $this->getHandler()->open($sessionId);
    }


    /**
     * Get hash for SID
     *
     * @return string
     */
    protected function _getHash()
    {
        return $this->_hashFunction();
    }


    /**
     * Set session handler
     *
     * @param SpecialSessionInterface $handler
     */
    public function setHandler(SpecialSessionInterface $handler)
    {
        $this->_sessionHandler = $handler;
    }


    /**
     * Get session handler
     *
     * @return SpecialSessionInterface
     */
    public function getHandler()
    {
        return $this->_sessionHandler;
    }



} 