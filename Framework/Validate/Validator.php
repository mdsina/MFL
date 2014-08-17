<?php
/**
 * Base framework validation functions by some patterns or types for secure queries
 *
 * @author Daniil Mikhailov <info@mdsina.ru>
 * @copyright Copyright (c) 2014, Daniil Mikhailov
 */

namespace Framework\Validate;

/**
 * Class Validator
 * @package Framework\Validate
 */
class Validator
{

    /**
     * Escape data
     *
     * @param mixed $data
     * @param string $type
     * @return mixed
     */
    public static function escapeData($data, $type)
    {

        if (empty($data)) {
            return [];
        }

        if (is_array($data)) {
            $result = [];

            foreach ($data as $item) {
                $result[] = self::escapeData($item, $type);
            }

            return $result;
        }

        switch($type) {
            case 'str':
                settype($data, 'string');
                break;
            case 'int':
                settype($data, 'integer');
                break;
            case 'float':
                settype($data, 'float');
                break;
            case 'bool':
                settype($data, 'boolean');
                break;
            case 'datetime':
                $data = trim($data);
                $data = preg_replace('/[^\d\-: ]/i', '', $data);
                preg_match('/^([\d]{2}-[\d]{2}-[\d]{4} [\d]{2}:[\d]{2}:[\d]{2})$/', $data, $matches);
                $data = $matches[1];
                break;
            case 'ts2dt':
                settype($data, 'integer');
                $data = date('d-m-Y H:i:s', $data);
                break;
            case 'hexcolor':
                preg_match('/(#[0-9abcdef]{6})/i', $data, $matches);
                $data = $matches[1];
                break;
            case 'email':
                $data = filter_var($data, FILTER_VALIDATE_EMAIL);
                break;
            default:
                $data = $data;
                break;
        }

        return $data;
    }
}