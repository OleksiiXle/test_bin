<?php

namespace app\widgets\binar;

use yii\web\AssetBundle;

class BinarAssets extends AssetBundle
{
    public $baseUrl = '@web/widgets/binar/assets';
    public $sourcePath = '@app/widgets/binar/assets';
    public $publishOptions = ['forceCopy' => true];
    public $css = [
        'css/binar.css',
    ];
    public $js = [
        'js/binar.js',
        'js/init.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
