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

class FunctionsHelper
{
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