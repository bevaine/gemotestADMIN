<?php
/**
 * Created by PhpStorm.
 * User: evgeny.dymchenko
 * Date: 21.03.2018
 * Time: 20:34
 */
namespace backend\assets;

use yii\web\AssetBundle;


class GmsAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/GMS/web';

    public $css = [
        'css/fancytree/ui.fancytree.css',
        'css/video-js.css',
        'css/bootstrap-material-datetimepicker.css',
        'css/bootstrap-timepicker.min.css'
        //'css/bootstrap-material-design.css',
        //'css/ripples.min.css',
    ];
    public $js = [
        'js/video.js',
        'js/jquery-ui.min.js',
        'js/fancytree/jquery.fancytree.js',
        'js/fancytree/jquery.fancytree.dnd.js',
        'js/fancytree/jquery.fancytree.edit.js',
        'js/fancytree/jquery.fancytree.table.js',
        'js/moment.js',
        'js/moment-with-locales.min.js',
        'js/bootstrap-material-datetimepicker.js',
        'js/bootstrap-timepicker.min.js',
        //'js/ripples.min.js',
        //'js/bootstrap.min.js',
        //'js/bootstrap-datetimepicker.min.js'
    ];

    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
        'yii\web\JqueryAsset',
    ];
}