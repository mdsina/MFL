<?php
/**
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\Request;

/**
 * Class User
 *
 * Wrapper for _SERVER's user info
 *
 * @package Framework\Request
 */
class User {

    /**
     * Get useragent string
     *
     * @return string
     */
    public static function getUserAgent()
    {
        return (string) $_SERVER['HTTP_USER_AGENT'];
    }


    /**
     * Get user ip
     *
     * @return string
     */
    public static function getIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip=$_SERVER['REMOTE_ADDR'];
        }

        return (string) $ip;
    }
} 