<?php declare(strict_types=1);

namespace Acme\Support\Utility;

class DateUtil
{
    /**
     * 日付文字列として有効か否かを返す (YYYY-mm-dd)
     * @param string $str
     * @return bool
     */
    public static function isDateString(string $str): bool
    {
        if (1 !== preg_match('/\A\d{4}-\d{2}-\d{2}\z/u', $str)) {
            return false;
        }
        if (false === ($intTime = strtotime($str))) {
            return false;
        }
        if ($str !== date('Y-m-d', $intTime)) {
            return false;
        }
        return true;
    }

    /**
     * 日付時刻文字列として有効ない中を返す (YYYY-mm-dd HH:ii:ss)
     * @param string $str
     * @return bool
     */
    public static function isDateTimeString(string $str): bool
    {
        if (1 !== preg_match('/\A\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\z/u', $str)) {
            return false;
        }
        if (false === ($intTime = strtotime($str))) {
            return false;
        }
        if ($str !== date('Y-m-d H:i:s', $intTime)) {
            return false;
        }
        return true;
    }
}
