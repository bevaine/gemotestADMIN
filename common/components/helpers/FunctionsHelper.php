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

    static function getDurationVideo($file)
    {
        try {
            $ffprobe = FFProbe::create(
                //[
                //'ffmpeg.binaries'  => Yii::getAlias('@common').'/bin/ffmpeg.exe',
                //'ffprobe.binaries' => Yii::getAlias('@common').'/bin/ffprobe.exe',
                //]
            );

            $duration = $ffprobe
                ->format($file)
                ->get('duration');

            Yii::getLogger()->log([
                'getDurationVideo' => $duration
            ], Logger::LEVEL_WARNING, 'binary');

            return !empty($duration) ? $duration : false;
        } catch (\Exception $exception) {
            Yii::getLogger()->log([
                'getDurationVideo' => $exception->getMessage()
            ], Logger::LEVEL_ERROR, 'binary');
            return false;
        }
    }
}