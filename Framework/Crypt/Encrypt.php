<?php
/**
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\Crypt;

/**
 * Class Encrypt
 *
 * Provide functions for some encryption
 *
 * @package Framework\Crypt
 */
class Encrypt {

    /**
     * Encrypt string
     *
     * @param string $string
     * @param string $key
     * @return string
     */
    public static function encrypt($string, $key) {
        $ivSize = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        $result = mcrypt_encrypt(MCRYPT_BLOWFISH, $key, utf8_encode($string), MCRYPT_MODE_ECB, $iv);

        return $result;
    }
} 