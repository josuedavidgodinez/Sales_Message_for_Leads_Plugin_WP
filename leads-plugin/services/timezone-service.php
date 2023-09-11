<?php
$path = __DIR__ . '/..';
require_once $path . "/api/api-calls.php";
require_once $path . "/env/constant-env-v.php";

/**
 * send message in sales message
 */
function wp_getHourOfTheTimeZone($timeZone)
{
    $response = wp_madePostAPI("/Etc/" . $timeZone, null, false, array(), endpointTimeZoneAPI());
    if (!is_null($response)) {
        $unixTimestamp = getBody($response)->unixtime;
        $hour24Format = date('G', $unixTimestamp); // Get the hour in 24-hour format
        return $hour24Format;
    }
    return null;
}

/**
 * send message in sales message
 */
function wp_getCurrentUTCwithAddSeconds($seconds)
{
    $current_utc_time = current_time('timestamp', true);
    $modified_utc_time = $current_utc_time + $seconds;
    return gmdate('Y-m-d\TH:i:s\Z', $modified_utc_time);
}


function GetAdjustedTimeZonewithAddedSeconds($qtyAdd, $timezone, $unity) {
    
    $range = RangeAvailableHours();
    // Define available hours range
    $start_hour = $range->minHour;
    $end_hour = $range->maxHour;
    $utc_timezone = new DateTimeZone('UTC');
    $target_tz = new DateTimeZone($timezone);

    $now = new DateTime('now', $target_tz);
    $now->modify("+$qtyAdd $unity"); // Add specified days
    $current_hour = (int) $now->format('G');

    if ($current_hour >= $start_hour && $current_hour < $end_hour) {
        $next_hour = clone $now;
    } else {
        $next_hour = clone $now;
        //last range of the day add 1 day after
        if ($current_hour<=24 && $current_hour>$end_hour) {
            $next_hour->modify('+1 day');
        }       
        $next_hour->setTime($start_hour, 0);
    }

    $next_hour->setTimezone($utc_timezone);
    return $next_hour->format('Y-m-d\TH:i:s\Z');
}