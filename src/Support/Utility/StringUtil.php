<?php declare(strict_types=1);

namespace Acme\Support\Utility;

class StringUtil
{
    /**
     * 正の整数(ゼロを含まない)か否かを返す
     * @param string $str
     * @return bool
     */
    public static function isPositiveInterger($str): bool
    {
        if (1 !== preg_match('/\A[1-9][0-9]*\z/u', $str)) {
            return false;
        }
        return true;
    }

    /**
     * (>= 0) の整数数字か否かを返す
     * @param string $str
     * @return bool
     */
    public static function isGteZeroInteger($str): bool
    {
        if (1 !== preg_match('/\A[0-9]+\z/u', $str)) {
            return false;
        }
        if (strlen($str) >= 2 && substr($str, 0, 1) === '0') {
            return false;
        }
        return true;
    }
}
