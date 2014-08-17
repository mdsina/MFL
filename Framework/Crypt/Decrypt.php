<?php
/**
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\Crypt;

/**
 * Class Decrypt
 * @package Framework\Crypt
 */
class Decrypt {

    /**
     * Decrypt encrypted string
     *
     * @param string $string
     * @param string $key
     * @return string
     */
    public static function decrypt($string, $key) {
        $ivSize = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        $result = mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $string, MCRYPT_MODE_ECB, $iv);

        return $result;
    }
} 