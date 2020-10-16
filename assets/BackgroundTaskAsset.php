<?php
namespace app\assets;

use yii\web\AssetBundle;

class BackgroundTaskAsset extends AssetBundle
{

    public $basePath = '@webroot/assets';
    public $sourcePath = '@app/assets';
    public $publishOptions = ['forceCopy' => true];
    public $css = [
    ];

    public $js = [
        'js/backgroundTask.js'
    ];

    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
