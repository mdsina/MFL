<?php
/**
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\Base;

/**
 * Class String
 * @package Framework\Base
 */
class String
{

    const INT_REGEX = '/[^0-9]/';

    /**
     * Return string with first uppercase symbol
     *
     * @param $str
     * @param string $encoding
     * @param bool $lowerStrEnd
     * @return string
     */
    public static function mbUcFirst($str, $encoding = "UTF-8", $lowerStrEnd = false) {
        $firstLetter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
        $strEnd = "";

        if ($lowerStrEnd) {
            $strEnd = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
        } else {
            $strEnd = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
        }

        $str = $firstLetter . $strEnd;
        return $str;
    }


    /**
     * Implode 1 level array to string likes 'foo = bar, fez = baz'
     *
     * @param array $array
     * @param string $delimiter
     * @param string $operator
     * @return string
     */
    public static function assocImplode(array $array = [], $delimiter = ', ', $operator = '=')
    {
        $result = implode(
            $delimiter,
            array_map(
                function ($o, $v, $k) {
                    return sprintf('%s%s%s', $k, $o, $v);
                },
                array($operator),
                $array,
                array_keys($array)
            )
        );

        return $result;
    }


    public static function itoa($number)
    {
        return preg_replace(static::INT_REGEX, '', $number);
    }
}