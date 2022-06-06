<?php

declare(strict_types=1);

namespace App\Utilities;

use ArrayAccess;

final class ArrayHelper
{
    public static function get($array, $key, $default = null)
    {
        if (!self::accessible($array)) {
            return $default;
        }

        if (\is_null($key)) {
            return $array;
        }

        if (self::exists($array, $key)) {
            return $array[$key];
        }

        if (\strpos($key, '.') === false) {
            return $array[$key] ?? $default;
        }

        foreach (\explode('.', $key) as $segment) {
            if (self::accessible($array) && self::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    public static function accessible($value): bool
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    public static function exists($array, $key): bool
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }
}