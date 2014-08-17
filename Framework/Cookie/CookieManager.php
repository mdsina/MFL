<?php
/**
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\Cookie;

/**
 * Class CookieManager
 * @package Framework\Cookie
 */
class CookieManager
{
    protected $_cookies = [];
    protected $_key = '';


    /**
     * Create cookies from global $_COOKIES
     */
    public function __construct($cryptKey)
    {
        if (empty($_COOKIE)) {
            return;
        }

        $this->_key = $cryptKey;

        foreach ($_COOKIE as $name => $value) {
            $cookieValue = @unserialize(\Framework\Crypt\Decrypt::decrypt($value, $this->_key));
            $this->_cookies[$name] = new Cookie($this->_key, $name, !$cookieValue ? $value : $cookieValue);
        }
    }

    /**
     * Return exist cookie or not
     *
     * @param string $cookieName
     * @return bool
     */
    public function exists($cookieName)
    {
        $cookieName = (string) $cookieName;
        return !empty($this->_cookies[$cookieName]);
    }


    /**
     * Add cookie to cookies array
     * If cookie already exists return false
     *
     * @param Cookie $cookie
     * @return bool
     */
    public function add(Cookie $cookie)
    {
        if ($this->exists($cookie->getName())) {
            return false;
        }

        $this->_cookies[$cookie->getName()] = $cookie;

        return true;
    }


    /**
     * Create cookie and add it to self container
     * return false if cookie already exists
     *
     * @param string $name
     * @param mixed $value
     * @param string|int $expirationDate
     * @param string $domain
     * @param string $path
     * @param bool $secure
     * @param bool $httpOnly
     * @return bool
     */
    public function create(
        $name, $value, $expirationDate = null, $domain = null, $path = null, $secure = null, $httpOnly = null
    ) {
        $name = (string) $name;

        if ($this->exists($name)) {
            return false;
        }

        $this->_cookies[$name] = new Cookie(
            $this->_key, $name, $value, $expirationDate, $domain, $path, $secure, $httpOnly
        );

        return true;
    }


    /**
     * get cookie object
     *
     * @param string $cookieName
     * @return Cookie|null
     */
    public function get($cookieName)
    {
        $cookieName = (string) $cookieName;
        return !empty($this->_cookies[$cookieName]) ? $this->_cookies[$cookieName] : null;
    }


    /**
     * Remove cookie
     *
     * @param string $cookieName
     * @return bool
     */
    public function remove($cookieName)
    {
        $cookieName = (string) $cookieName;

        if (!$cookie = $this->get($cookieName)) {
            return false;
        }

        unset ($this->_cookies[$cookieName]);
        return $cookie->deactivate();;
    }


    /**
     * Check activated cookie
     *
     * @param string $cookieName
     * @return bool
     */
    public function activated($cookieName)
    {
        $cookieName = (string) $cookieName;

        if (!$this->exists($cookieName)) {
            return false;
        }

        return !empty($_COOKIE[$cookieName]);
    }


    /**
     * Activate cookie
     *
     * @param string $cookieName
     * @return bool
     */
    public function activate($cookieName)
    {
        $cookieName = (string) $cookieName;

        if ($this->activated($cookieName) || !($cookie = $this->get($cookieName))) {
            return false;
        }

        return $cookie->activate();
    }


    /**
     * Update cookie
     * return false if cookie doesn't exists
     *
     * @param string $name
     * @param mixed $value
     * @param string|int $expirationDate
     * @param string $domain
     * @param string $path
     * @param bool $secure
     * @param bool $httpOnly
     * @return bool
     */
    public function update(
        $name, $value = null, $expirationDate = null, $domain = null, $path = null, $secure = null, $httpOnly = null
    ) {
        $name = (string) $name;

        if (!$this->exists($name)) {
            return false;
        }

        $this->_cookies[$name] = new Cookie(
            $this->_key, $name, $value, $expirationDate, $domain, $path, $secure, $httpOnly
        );

        return true;
    }

}