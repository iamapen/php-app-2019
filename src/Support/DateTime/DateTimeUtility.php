<?php declare(strict_types=1);

namespace Acme\Support\DateTime;

/**
 * 日付ユーティリティ
 */
class DateTimeUtility
{

    /**
     * @param int $intWeek
     * @return string|null
     */
    public static function toJpWeek($intWeek)
    {
        $map = [0 => '日', 1 => '月', 2 => '火', 3 => '水', 4 => '木', 5 => '金', 6 => '土'];
        if (!isset($map[$intWeek])) {
            return null;
        }
        return $map[$intWeek];
    }

    /**
     * 秒を hh:mm:ss 形式にして返す
     * @param int $seconds
     * @return string
     */
    public static function secondsToHms($seconds)
    {
        $seconds = abs($seconds);
        $secondPart = $seconds % 60;
        $diffMinutes = ($seconds - $secondPart) / 60;
        $minutePart = $diffMinutes % 60;
        $hourPart = ($diffMinutes - $minutePart) / 60;
        return sprintf('%02d:%02d:%02d', $hourPart, $minutePart, $secondPart);
    }

    /**
     * 時刻の差を hh:mm:ss 形式にして返す
     * @param \DateTimeInterface $dtStart
     * @param \DateTimeInterface $dtEnd
     * @return string
     */
    public static function diffToHms(\DateTimeInterface $dtStart, \DateTimeInterface $dtEnd)
    {
        return static::secondsToHms($dtEnd->getTimestamp() - $dtStart->getTimestamp());
    }

    /**
     * 時刻の差を hh:mm:ss 形式にして返す
     * @param int $intStart
     * @param int $intEnd
     * @return string
     */
    public static function diffToHmsByInt($intStart, $intEnd)
    {
        return static::secondsToHms($intEnd - $intStart);
    }
}
