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

    static function JsHiddenInputAdd($playListKeyStr) {
        return <<< EOT

        var playListKey = "$playListKeyStr";
        var parentFolder = $("#fancyree_output_list").fancytree("getTree").getNodeByKey(playListKey); 

        if ($("input").is("#gmsplaylist-jsonplaylist")) {
            $("#gmsplaylist-jsonplaylist").remove();
        }                    
        
        if ($("input").is("#gmsplaylist-name")) {
            $("#gmsplaylist-name").remove();
        }
            
        if (parentFolder !== null) { 
        
            var arrJson = []; 
            var arrOut = {};
            var arrChildrenOne = [];
            var rootTitle = parentFolder.title;
            
            arrOut["key"] = playListKey;
            arrOut["title"] = rootTitle;
            arrOut["folder"] = "true";
            arrOut["expanded"] = "true";

            $("<input>").attr({
                type: "hidden",
                id: "gmsplaylist-name",
                name: "GmsPlaylist[name]",
                value: rootTitle
            }).appendTo("form");
            
            if (parentFolder.children !== null) {
                parentFolder.children.forEach(function(children) {
                    var arrChildren = {};
                    var key = children.key;
                    var name = children.title;
                    arrChildren["key"] = key; 
                    arrChildren["title"] = name;
                    arrChildrenOne.push(arrChildren); 
                });
    
                arrOut["children"] = arrChildrenOne;
                arrJson.push(arrOut);
                var jsonStr = JSON.stringify(arrJson);
                //console.log(jsonStr);
    
                $("<input>").attr({
                    type: "hidden",
                    id: "gmsplaylist-jsonplaylist",
                    name: "GmsPlaylist[jsonPlaylist]",
                    value: jsonStr
                }).appendTo("form");
            }
        }
EOT;
    }


    static function getDurationVideo($file)
    {
        try {
            $ffprobe = FFProbe::create([
                'ffmpeg.binaries'  => Yii::getAlias('@common').'/bin/ffmpeg.exe',
                'ffprobe.binaries' => Yii::getAlias('@common').'/bin/ffprobe.exe',
            ]);

            $duration = $ffprobe
                ->format($file)
                ->get('duration');

            return !empty($duration) ? $duration : false;
        } catch (\Exception $exception) {
            return false;
        }
    }
}