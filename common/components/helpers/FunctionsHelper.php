<?php
/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 30.01.2018
 * Time: 9:15
 */

namespace common\components\helpers;

use FFMpeg\FFProbe;
use Yii;
use yii\log\Logger;
use DateTimeZone;
use DateTime;

class FunctionsHelper
{
    static $timezone = [
        '2' => 'Europe/Kaliningrad',
        '3' => 'Europe/Moscow',
        '4' => 'Europe/Samara',
        '5' => 'Asia/Yekaterinburg',
        '6' => 'Asia/Novosibirsk',
        '7' => 'Asia/Novokuznetsk',
        '8' => 'Asia/Irkutsk',
        '9' => 'Asia/Yakutsk',
        '10' => 'Asia/Magadan',
        '11' => 'Asia/Sakhalin',
        '12' => 'Asia/Kamchatka',
    ];

    static $timezones = [
        'Europe/Kaliningrad',
        'Europe/Moscow',
        'Europe/Samara',
        'Asia/Yekaterinburg',
        'Asia/Novosibirsk',
        'Asia/Novokuznetsk',
        'Asia/Irkutsk',
        'Asia/Yakutsk',
        'Asia/Magadan',
        'Asia/Sakhalin',
        'Asia/Kamchatka',
    ];

    /**
     * @return array
     */
    static function getTimeZonesList() {
        $arr_out = [];
        foreach (self::$timezones as $timezone) {
            $MNTTZ = new DateTimeZone($timezone);
            $dt = new DateTime(null, $MNTTZ);
            $arr_out[$timezone] = $timezone." ".$dt->format('P');
        }
        return $arr_out;
    }

    /**
     * @return array
     */
    static function getHourZonesList() {
        $arr_out = [];
        foreach (self::$timezones as $timezone) {
            $MNTTZ = new DateTimeZone($timezone);
            $dt = new DateTime(null, $MNTTZ);
            $arr_out[$timezone] = $dt->format('P');
        }
        return $arr_out;
    }

    /**
     * @param $time
     * @param $ZoneTo
     * @param string $ZoneFrom
     * @return mixed
     */
    static function getTimestampForTimeZone($time, $ZoneTo, $ZoneFrom = 'Europe/Moscow')
    {
        $dateTimeZoneTo = date_create('now', timezone_open($ZoneTo));
        $dateTimeZoneFrom = date_create('now', timezone_open($ZoneFrom));



        $difference = date_offset_get($dateTimeZoneFrom) - date_offset_get($dateTimeZoneTo);

        Yii::getLogger()->log([
            '$dateTimeZoneTo' => $dateTimeZoneTo ,
            '$dateTimeZoneFrom' => $dateTimeZoneFrom,
            '$difference' => $difference,
            '$return' => date("Y-m-d H:i:s", $time - $difference)
        ], 1, 'binary');

        return $time - $difference;
    }

    /**
     * @param $url
     * @return string
     */
    static function AjaxInitScript($url)
    {
        return <<< SCRIPT
        function (element, callback) {
            var id=\$(element).val();
            if (id !== "") {
                \$.ajax("{$url}?id=" + id, {
                    dataType: "json"
                }).done(function(data) { callback(data.results);});
            }
        }
SCRIPT;
    }

    /**
     * @param $file
     * @return bool|mixed
     */
    static function getDurationVideo($file)
    {
        try {
            $ffprobe = FFProbe::create(self::getBinFFmpeg());

            $duration = $ffprobe
                ->format($file)
                ->get('duration');
            return !empty($duration) ? $duration : false;

        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @param $srcFile
     * @param string $destFile
     * @return bool|string
     */
    public static function createMovieThumb($srcFile, $destFile = "unknown.jpg")
    {
        $output = array();
        try {
            $cmd = sprintf('%s -i %s -an -ss 00:00:05 -vf scale=150:-2 -r 1 -vframes 1 -y %s',
                self::getBinFFmpeg()['ffmpeg.binaries'], $srcFile, $destFile);

            exec($cmd, $output, $retval);
            if ($retval) return false;
            return $destFile;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @return array
     */
    static function getBinFFmpeg () {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return [
                'ffmpeg.binaries'  => Yii::getAlias('@common').'\\bin\\ffmpeg.exe',
                'ffprobe.binaries' => Yii::getAlias('@common').'\\bin\\ffprobe.exe',
            ];
        } else {
            return [
                'ffmpeg.binaries'  => '/usr/bin/ffmpeg' ,
                'ffprobe.binaries' => '/usr/bin/ffprobe',
            ];
        }
    }
}