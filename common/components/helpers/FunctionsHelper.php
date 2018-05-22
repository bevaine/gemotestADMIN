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
use yii\helpers\ArrayHelper;

class FunctionsHelper
{
    static $timezones = [
        'Europe/Kaliningrad',   //+2
        'Europe/Moscow',        //+3
        'Europe/Samara',        //+4
        'Asia/Yekaterinburg',   //+5
        'Asia/Novosibirsk',     //+6
        'Asia/Novokuznetsk',    //+7
        'Asia/Irkutsk',         //+8
        'Asia/Yakutsk',         //+9
        'Asia/Magadan',         //+10
        'Asia/Sakhalin',        //+11
        'Asia/Kamchatka',       //+12
    ];

    /**
     * @param $all_time
     * @param $arr_commerce
     * @param $arr_standart
     * @param int $minimal_std
     * @return array
     */
    public function calcViewCount($all_time, $arr_commerce, $arr_standart, $minimal_std = 60)
    {
        $f = [];
        $s = [];
        $sum = 0;
        $std_time = 0;
        $com_time = 0;

        foreach ($arr_commerce as $input) {
            $sum += $input['duration'] * $input['repeat'];
        }
        $play_standart = ($all_time - $sum) / array_sum(ArrayHelper::getColumn($arr_commerce, 'repeat'));
        $play_standart = round($play_standart);

        if ($play_standart >= $minimal_std) {

            foreach ($arr_commerce as $commerce) {
                $arr = array_fill(0, $commerce['repeat'], [
                    'file' => $commerce['file'],
                    'key' => $commerce['key'],
                    'start' => 0,
                    'end' => $commerce['duration']
                ]);
                if (empty($f)) $f = $arr;
                else $f = array_merge($f, $arr);
                if (!empty($f)) shuffle($f);
            }

            foreach ($arr_standart as $time) {
                for ($a = 0; ;($a = $a + $play_standart)) {
                    $b = $a - $play_standart;
                    if ($a > $time['duration']) {
                        $s[] = [
                            'file' => $time['file'],
                            'key' => $time['key'],
                            'start' => $b,
                            'end' => $time['duration']
                        ];
                        $std_time = $std_time + ($time['duration'] - $b);
                        break;
                    } elseif ($a > 0) {
                        $val = each($f)['value'];
                        if (empty($val)) {
                            $s[] = [
                                'file' => $time['file'],
                                'key' => $time['key'],
                                'start' => $b,
                                'end' => $time['duration']
                            ];
                            $std_time = $std_time + ($time['duration'] - $b);
                            break;
                        }
                        $s[] = [
                            'file' => $time['file'],
                            'key' => $time['key'],
                            'start' => $b,
                            'end' => $a
                        ];
                        $s[] = $val;
                        $std_time = $std_time + ($a - $b);
                        $com_time = $com_time + $val['end'];
                    }
                }
            }

            if (!empty($s)) {
                return [
                    'com_time' => $com_time,
                    'std_time' => $std_time,
                    'state' => 1,
                    'info' => $s
                ];
            } else {
                return [
                    'state' => 0,
                    'message' => 'Ошибка формирования плейлиста дневного эфира!'
                ];
            }
        } else {
            $message = 'Слишком короткий интервал бесплатного эфирного время ' . $play_standart . 'сек. (из допущенного ' . $minimal_std . ' сек.)';
            $message .= '<br>Уменьшите интервал и/или кол-во просмотра коммерческого видео, чтобы уложиться в время дневого эфира - '.$all_time.' сек.';
            return [
                'state' => 0,
                'message' => $message
            ];
        }
    }

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
    static function getInfoVideo($file)
    {
        $rate = 0;

        try {
            $ffprobe = FFProbe::create(self::getBinFFmpeg());

            /** @var FFProbe\DataMapping\Stream $test */
            $stream = $ffprobe
                ->streams($file)
                ->videos()
                ->first();

            $duration = (int)$stream->get('duration');
            $all_frames = (int)$stream->get('nb_frames');
            $height = (int)$stream->getDimensions()->getHeight();
            $width = (int)$stream->getDimensions()->getWidth();

            if (empty($duration)) return false;

            if (empty($all_frames)) {
                $avg_frame_rate = $ffprobe
                    ->streams($file)
                    ->videos()
                    ->first()
                    ->get('avg_frame_rate');
                    $avg_frame_rate_exp = explode("/", $avg_frame_rate);
                    if (!empty($avg_frame_rate_exp[0])
                        && !empty($avg_frame_rate_exp[1])) {
                        $rate = round($avg_frame_rate_exp[0] / $avg_frame_rate_exp[1]);
                        $all_frames = (int)$rate * (int)$duration;
                    }
            } else {
                $rate = (int)$all_frames / (int)$duration;
            }

            return [
                'width' => $width,
                'height' => $height,
                'duration' => $duration,
                'nb_frames' => $all_frames,
                'frame_rate' => $rate
            ];

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