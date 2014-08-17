<?php
/**
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\Cookie;

/**
 * Class Cookie
 * @package Framework\Cookie
 */
class Cookie implements CookieInterface
{

    /**
     * Expiration date, when cookie will be removed
     *
     * default: 0 (cookie will be removed when current session will be closed)
     *
     * @var int
     */
    protected $_expirationDate;


    /**
     * Cookie's name
     *
     * @var string
     */
    protected $_name;


    /**
     * Cookie's value
     *
     * @var mixed
     */
    protected $_value;


    /**
     * Sets whether it is possible to use a different protocol other than HTTP
     *
     * default: false
     *
     * @var bool
     */
    protected $_httpOnly;


    /**
     * Domain that are available cookie
     * More: http://php.net/manual/ru/function.setcookie.php
     *
     * @var string
     */
    protected $_domain;


    /**
     * The path to the directory on the server, which will be available from the cookie.
     * More: http://php.net/manual/ru/function.setcookie.php
     *
     * @var string
     */
    protected $_path;


    /**
     * Indicates that the cookie should be transmitted from the client via a secure HTTPS connection or not
     *
     * @var bool
     */
    protected $_secure;


    /**
     * DateTime pattern
     *
     * Need to convert human date to timestamp
     * Default pattern: 'd-m-Y H:i:s'
     *
     * @var string
     */
    protected $_dateTimePattern = 'd-m-Y H:i:s';


    /**
     * Encryption key
     *
     * @var string
     */
    protected $_key;


    /**
     * Create cookie
     *
     * @param string $encryptionKey
     * @param string $name
     * @param string $value
     * @param string $expirationDate
     * @param string $domain
     * @param string $path
     * @param bool $secure
     * @param bool $httpOnly
     */
    public function __construct(
        $encryptionKey, $name, $value = '', $expirationDate = '0',
        $domain = '', $path = '', $secure = false, $httpOnly = false
    ) {
        $this->_name = (string) $name;
        $expirationDate = !empty($value['life_time']) ? $value['life_time'] : $expirationDate;

        if (!empty($value['life_time'])) {
            unset ($value['life_time']);
        }

        $this->_key = (string) $encryptionKey;

        $this->setValue($value);
        $this->setExpirationDate($expirationDate);

        $this->_domain = (string) $domain;
        $this->_path = (string) $path;
        $this->_secure = (bool) $secure;
        $this->_httpOnly = (bool) $httpOnly;
    }


    /**
     * Activate cookie
     *
     * before sending the cookie data serialized and date convert to timestamp
     *
     * @return bool
     */
    public function activate()
    {
        $cookieName = $this->_name;
        $cookieValue = \Framework\Crypt\Encrypt::encrypt(serialize($this->_value), $this->_key);

        return setcookie(
            $cookieName, $cookieValue, $this->_expirationDate, $this->_path,
            $this->_domain, $this->_secure, $this->_httpOnly
        );
    }


    /**
     * Deactivate cookie
     *
     * @return bool
     */
    public function deactivate()
    {
        if (empty($_COOKIE[$this->_name])) {
            return true;
        }

        //10800 - 1st January 1970
        return setcookie($this->getName(), null, 10800);
    }


    /**
     * set cookie value
     * must be sets before activating
     *
     * @param mixed $value
     */
    public function setValue($value)
    {
        if (!is_array($value)) {
            $value = array('value' => $value);
        }

        if (empty($value['value'])) {
            $value = ['value' => $value];
        }

        $value['life_time'] = $this->_expirationDate;
        $this->_value = $value;
    }


    /**
     * set cookie name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = (string) $name;
    }


    /**
     * Get cookie's name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }


    /**
     * Get cookie value
     *
     * @return array
     */
    public function getValue()
    {
        return $this->_value;
    }


    /**
     * set expiration date of cookie
     *
     * if passed as number will be used as a ready-timestamp
     *
     * @param string|int $date
     */
    public function setExpirationDate($date)
    {
        $expirationDate = \Framework\Base\String::itoa($date);

        if (is_string($date) && (strlen($expirationDate) < strlen($date))) {
            $expirationDate = \DateTime::createFromFormat($this->_dateTimePattern, $this->_expirationDate);
            $expirationDate = $expirationDate->getTimestamp();
        }

        $this->_expirationDate = $expirationDate;
        $this->_value['life_time'] = $this->_expirationDate;
    }


    /**
     * DateTime pattern
     *
     * @param string $pattern
     */
    public function setDateTimePattern($pattern)
    {
        $pattern = (string) $pattern;
        $this->_dateTimePattern = $pattern;
    }


    public function getLifeTime()
    {
        return $this->_value['life_time'];
    }
}

