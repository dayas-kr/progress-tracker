<?php

namespace App\Helpers;

class DurationConverter
{
    /**
     * Converts duration in format PnYnMnDTnHnMnS to array
     * @param $duration
     * @return array|null
     */
    public static function convertDuration($duration)
    {
        if (!is_string($duration)) {
            return null;
        }

        $numberRegExp = '\\d+(?:[.,]\\d+)?';
        // Updated regex with non-capturing groups and only capturing the numbers
        $durationRegExp = "/^P(?:($numberRegExp)Y)?(?:($numberRegExp)M)?(?:($numberRegExp)D)?(?:T(?:($numberRegExp)H)?(?:($numberRegExp)M)?(?:($numberRegExp)S)?)?$/";

        if (!preg_match($durationRegExp, $duration, $matches)) {
            return null;
        }

        $keys = ['year', 'month', 'day', 'hour', 'minute', 'second'];
        $durationResult = [];

        foreach ($keys as $index => $key) {
            $matchIndex = $index + 1;
            if (isset($matches[$matchIndex]) && $matches[$matchIndex] !== '') {
                $value = floatval(str_replace(',', '.', $matches[$matchIndex]));
                // If value is whole, cast to int.
                $durationResult[$key] = ($value == intval($value)) ? intval($value) : $value;
            } else {
                $durationResult[$key] = null;
            }
        }

        return $durationResult;
    }

    /**
     * Converts duration in format PnYnMnDTnHnMnS to seconds
     * @param $duration
     * @return int|null
     */
    public static function convertToSecond($duration)
    {
        $durationResult = self::convertDuration($duration);
        if (!$durationResult) {
            return null;
        }

        $seconds = 0;
        foreach ($durationResult as $key => $value) {
            switch ($key) {
                case 'year':
                    $seconds += $value * 365 * 24 * 60 * 60;
                    break;
                case 'month':
                    $seconds += $value * 30 * 24 * 60 * 60;
                    break;
                case 'day':
                    $seconds += $value * 24 * 60 * 60;
                    break;
                case 'hour':
                    $seconds += $value * 60 * 60;
                    break;
                case 'minute':
                    $seconds += $value * 60;
                    break;
                case 'second':
                    $seconds += $value;
                    break;
            }
        }

        return ($seconds == intval($seconds)) ? intval($seconds) : $seconds;
    }

    /**
     * Converts duration in format PnYnMnDTnHnMnS to HH:MM:SS
     * @param $duration
     * @return string|null
     */
    public static function convertYouTubeDuration($duration)
    {
        $durationResult = self::convertDuration($duration);
        if (!$durationResult) {
            return null;
        }

        $hour = 0;
        $minute = 0;
        $second = 0;

        if (isset($durationResult['year'])) {
            $hour += $durationResult['year'] * 365 * 24;
        }
        if (isset($durationResult['month'])) {
            $hour += $durationResult['month'] * 30 * 24;
        }
        if (isset($durationResult['day'])) {
            $hour += $durationResult['day'] * 24;
        }
        if (isset($durationResult['hour'])) {
            $hour += $durationResult['hour'];
        }
        if (isset($durationResult['minute'])) {
            $minute += $durationResult['minute'];
        }
        if (isset($durationResult['second'])) {
            $second += $durationResult['second'];
        }

        // Normalize fractional parts
        $minute += ($hour - floor($hour)) * 60;
        $hour = floor($hour);

        while ($minute >= 60) {
            $hour++;
            $minute -= 60;
        }

        $second += ($minute - floor($minute)) * 60;
        $minute = floor($minute);

        while ($second >= 60) {
            $minute++;
            $second -= 60;
        }

        while ($minute >= 60) {
            $hour++;
            $minute -= 60;
        }

        $second = round($second);

        // If there's no hour (cast to int for safe comparison), return minute:second format
        if ((int)$hour === 0) {
            return $minute . ':' . str_pad($second, 2, '0', STR_PAD_LEFT);
        }

        // Otherwise, return hour:minute:second (with minute and second padded)
        return $hour . ':' . str_pad($minute, 2, '0', STR_PAD_LEFT) . ':' . str_pad($second, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Converts a given number of seconds into YouTube duration format.
     * If the duration is less than one hour, the format will be "m:ss".
     * Otherwise, the format will be "h:mm:ss".
     *
     * @param mixed $seconds
     * @return string|null
     */
    public static function convertSecondsToYouTubeDuration($seconds)
    {
        if (!is_numeric($seconds) || $seconds < 0) {
            return null;
        }

        $seconds = (int) round($seconds);

        // Use a simple conditional based on total seconds.
        if ($seconds < 3600) {
            $minute = floor($seconds / 60);
            $sec = $seconds % 60;
            return $minute . ':' . str_pad($sec, 2, '0', STR_PAD_LEFT);
        }

        $hour = floor($seconds / 3600);
        $remainder = $seconds % 3600;
        $minute = floor($remainder / 60);
        $sec = $remainder % 60;

        return $hour . ':' . str_pad($minute, 2, '0', STR_PAD_LEFT) . ':' . str_pad($sec, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Converts a given number of seconds into an ISO 8601 duration string (PnYnMnDTnHnMnS).
     * The conversion uses the following assumptions:
     * - 1 year = 365 days
     * - 1 month = 30 days
     * - 1 day = 24 hours
     *
     * @param mixed $seconds
     * @return string|null
     */
    public static function convertSecondsToDuration($seconds)
    {
        if (!is_numeric($seconds) || $seconds < 0) {
            return null;
        }

        $seconds = (int) round($seconds);

        // Special case for zero seconds
        if ($seconds === 0) {
            return 'PT0S';
        }

        $yearSeconds  = 365 * 24 * 60 * 60;
        $monthSeconds = 30 * 24 * 60 * 60;
        $daySeconds   = 24 * 60 * 60;

        $years   = floor($seconds / $yearSeconds);
        $seconds -= $years * $yearSeconds;

        $months  = floor($seconds / $monthSeconds);
        $seconds -= $months * $monthSeconds;

        $days    = floor($seconds / $daySeconds);
        $seconds -= $days * $daySeconds;

        $hours   = floor($seconds / 3600);
        $seconds -= $hours * 3600;

        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;

        $duration = 'P';
        if ($years > 0) {
            $duration .= $years . 'Y';
        }
        if ($months > 0) {
            $duration .= $months . 'M';
        }
        if ($days > 0) {
            $duration .= $days . 'D';
        }

        $time = '';
        if ($hours > 0) {
            $time .= $hours . 'H';
        }
        if ($minutes > 0) {
            $time .= $minutes . 'M';
        }
        if ($seconds > 0) {
            $time .= $seconds . 'S';
        }

        if ($time !== '') {
            $duration .= 'T' . $time;
        }

        return $duration;
    }
}
