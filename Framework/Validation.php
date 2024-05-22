<?php

namespace Framework;

use LengthException;

class Validation
{
    /**
     * Validate string
     * 
     * @param string $value
     * @param int $min
     * @param int $max
     * @return bool
     */
    public static function string($value, $min = 1, $max = INF)
    {
        if (is_string($value)) {
            $value = trim($value);
            $length = strlen($value);
            return $length >= $min && $length <= $max;
        }

        return false;
    }

    /**
     * Validate email
     * 
     * @param string $value
     * @return mixed
     */
    public static function email($value)
    {
        $value = trim($value);

        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Match two strings values
     * 
     * @param string $value
     * @param string $value2
     * return bool
     */
    public static function match($value, $value2)
    {
        $value = trim($value);
        $value2 = trim($value2);
        return $value === $value2;
    }
}
