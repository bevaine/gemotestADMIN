<?php
/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 30.01.2018
 * Time: 9:15
 */

namespace common\components\helpers;


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
}