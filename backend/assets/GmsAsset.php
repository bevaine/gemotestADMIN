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
    ];
    public $js = [
        'js/jquery-ui.min.js',
        'js/fancytree/jquery.fancytree.js',
        'js/fancytree/jquery.fancytree.dnd.js',
        'js/fancytree/jquery.fancytree.edit.js',
        'js/fancytree/jquery.fancytree.table.js',
        'js/moment.js',
        'js/video.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
        'yii\web\JqueryAsset',
    ];
}